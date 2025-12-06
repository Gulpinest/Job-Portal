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
        $lowongans = Lowongan::where('id_company', $company->id_company)->latest()->get();
        return view('lowongans.index', compact('lowongans'));
    }

    public function create()
    {
        $allSkills = Skill::all(); 
        $selectedSkills = [];      

        return view('lowongans.create', compact('allSkills', 'selectedSkills'));
    }

    public function store(Request $request)
    {
        // 1. PERBAIKAN VALIDASI (Agar Gaji, Lokasi, dll tidak kosong)
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
            'keterampilan' => $request->keterampilan,
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