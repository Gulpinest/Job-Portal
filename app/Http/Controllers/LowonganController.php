<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\LowonganSkill;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LowonganController extends Controller
{
    public function index()
    {
        $company = Auth::user()->company;
        $lowongans = Lowongan::where('id_company', $company->id_company)
            ->with('skills')
            ->withCount('lamarans')
            ->latest()
            ->paginate(5);
        return view('lowongans.index', compact('lowongans'));
    }

    public function create()
    {
        $company = Auth::user()->company;

        // Check if company is verified
        if (!$company->is_verified) {
            return redirect()->route('lowongans.index')
                            ->with('error', 'Akun perusahaan Anda belum diverifikasi oleh administrator. Silahkan tunggu persetujuan admin.');
        }

        if ($company->package) {
            if ($company->job_quota <= 0) {
                return redirect()->route('pricing')
                    ->with('error', 'Kuota lowongan Anda sudah habis. Silakan upgrade paket.');
            }
        }

        $allSkills = Skill::all(); 
        $selectedSkills = [];      

        return view('lowongans.create', compact('allSkills', 'selectedSkills'));
    }

    public function store(Request $request)
    {
        $company = Auth::user()->company;

        // Check if company is verified
        if (!$company->is_verified) {
            return redirect()->back()
                            ->with('error', 'Akun perusahaan Anda belum diverifikasi. Tidak bisa membuat lowongan.');
        }

        if ($company->package && $company->job_quota <= 0) {
            return redirect()->back()
                ->with('error', 'Kuota lowongan Anda sudah habis. Silakan upgrade paket.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi_kantor' => 'required|string|max:255', // Tambahan
            'gaji' => 'required|string|max:255',          // Tambahan
            'keterampilan' => 'required|string',          // Tambahan
            'tipe_kerja' => 'required|string',            // Tambahan
            'deskripsi' => 'required|string',
            'status' => 'required|in:Open,Closed',
            'skills' => 'array',
            'skills.*' => 'string|max:255',
        ]);

        // 2. Definisi Company ID (SUDAH BENAR)
        $companyId = Auth::user()->company->id_company;

        // 3. Simpan Data
        $lowongan = Lowongan::create([
            'id_company' => $companyId, // Menggunakan variabel yang benar
            'judul' => $request->judul,
            'posisi' => $request->posisi,
            'lokasi_kantor' => $request->lokasi_kantor,
            'gaji' => $request->gaji,
            'tipe_kerja' => $request->tipe_kerja,
            'persyaratan_tambahan' => $request->persyaratan_tambahan,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'tipe_kerja' => $request->tipe_kerja,
        ]);

        // 4. Simpan Skills
        if ($request->filled('skills')) {
            foreach ($request->skills as $skill) {
                if (!empty($skill)) {
                    LowonganSkill::create([
                        'id_lowongan' => $lowongan->id_lowongan,
                        'nama_skill' => $skill,
                    ]);
                }
            }
        }

        // 5. Kurangi Kuota (SUDAH BENAR)
        $user = Auth::user();
        $user->company->decrement('job_quota'); 

        return redirect()->route('lowongans.index')->with('success', 'Lowongan baru berhasil ditambahkan!');
    }

    public function show(Lowongan $lowongan)
    {
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403, 'Akses Ditolak.');
        }

        // Load relationships
        $lowongan->load('skills', 'lamarans.pelamar', 'interviewSchedule');

        // Get lamaran stats
        $lamarans = $lowongan->lamarans()->with('pelamar')->get();
        $pendingCount = $lamarans->where('status_ajuan', 'Pending')->count();
        $acceptedCount = $lamarans->where('status_ajuan', 'Accepted')->count();
        $rejectedCount = $lamarans->where('status_ajuan', 'Rejected')->count();
        $interviewScheduled = $lowongan->interviewSchedule !== null;

        return view('lowongans.show', compact(
            'lowongan',
            'lamarans',
            'pendingCount',
            'acceptedCount',
            'rejectedCount',
            'interviewScheduled'
        ));
    }

    public function edit(Lowongan $lowongan)
    {
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403, 'Akses Ditolak.');
        }

        $allSkills = Skill::all(); 
        $selectedSkills = $lowongan->skills->pluck('nama_skill')->toArray();

        return view('lowongans.edit', compact('lowongan', 'allSkills', 'selectedSkills'));
    }

    public function update(Request $request, Lowongan $lowongan)
    {
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403);
        }

        // 6. PERBAIKAN VALIDASI DI UPDATE (Samakan dengan Store)
        $request->validate([
            'judul' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi_kantor' => 'required|string|max:255', // Tambahan
            'gaji' => 'required|string|max:255',          // Tambahan
            'keterampilan' => 'required|string',          // Tambahan
            'tipe_kerja' => 'required|string',            // Tambahan
            'deskripsi' => 'required|string',
            'status' => 'required|in:Open,Closed',
            'skills' => 'array',
            'skills.*' => 'string|max:255',
        ]);

        // 7. PERBAIKAN UPDATE (Agar field baru ikut tersimpan saat diedit)
        $lowongan->update([
            'judul' => $request->judul,
            'posisi' => $request->posisi,
            'lokasi_kantor' => $request->lokasi_kantor,
            'gaji' => $request->gaji,
            'keterampilan' => $request->keterampilan,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'tipe_kerja' => $request->tipe_kerja,
        ]);

        // Update skills: hapus lama, simpan ulang
        LowonganSkill::where('id_lowongan', $lowongan->id_lowongan)->delete();

        if ($request->filled('skills')) {
            foreach ($request->skills as $skill) {
                if (!empty($skill)) {
                    LowonganSkill::create([
                        'id_lowongan' => $lowongan->id_lowongan,
                        'nama_skill' => $skill,
                    ]);
                }
            }
        }

        return redirect()->route('lowongans.index')
                         ->with('success', 'Lowongan berhasil diperbarui!');
    }

    public function destroy(Lowongan $lowongan)
    {
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403);
        }

        $lowongan->delete();

        return redirect()->route('lowongans.index')
                         ->with('success', 'Lowongan berhasil dihapus!');
    }
}