<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\LowonganSkill; 
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $allSkills = Skill::all(); // semua skill master
        $selectedSkills = [];      // belum ada yang dipilih

        return view('lowongans.create', compact('allSkills', 'selectedSkills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:Open,Closed',
            'skills' => 'array', // 🆕 validasi tambahan
            'skills.*' => 'string|max:255',
        ]);

        $company = Auth::user()->company;

        // Buat lowongan baru
        $lowongan = Lowongan::create([
            'id_company' => $company->id_company,
            'judul' => $request->judul,
            'posisi' => $request->posisi,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        // Simpan skill yang dibutuhkan ke tabel lowongan_skill
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

        return redirect()->route('lowongans.index')->with('success', 'Lowongan baru berhasil ditambahkan!');
    }

    public function edit(Lowongan $lowongan)
    {
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403, 'AKSES DITOLAK');
        }

        // 🆕 Ambil skill-skill yang sudah ada agar bisa ditampilkan di form edit
        $allSkills = Skill::all(); // master skill
        $selectedSkills = $lowongan->skills->pluck('nama_skill')->toArray();

        return view('lowongans.edit', compact('lowongan', 'allSkills', 'selectedSkills'));
    }

    public function update(Request $request, Lowongan $lowongan)
    {
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403, 'AKSES DITOLAK');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:Open,Closed',
            'skills' => 'array',  
            'skills.*' => 'string|max:255',
        ]);

        // Update data utama lowongan
        $lowongan->update($request->only(['judul', 'posisi', 'deskripsi', 'status']));

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

        return redirect()->route('lowongans.index')->with('success', 'Lowongan berhasil diperbarui!');
    }

    public function destroy(Lowongan $lowongan)
    {
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403, 'AKSES DITOLAK');
        }

        $lowongan->delete();

        return redirect()->route('lowongans.index')->with('success', 'Lowongan berhasil dihapus!');
    }

}
