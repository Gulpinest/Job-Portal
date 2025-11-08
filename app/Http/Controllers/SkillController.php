<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    public function index()
    {
        $pelamar = Pelamar::where('id_user', Auth::id())->first();
        $skills = $pelamar ? $pelamar->skills : collect();

        return view('skills.index', compact('skills'));
    }

    public function create()
    {
        return view('skills.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'nama_skill' => 'required|string|max:100',
        ]);

        $pelamar = Pelamar::where('id_user', Auth::id())->first();

        if (!$pelamar) {
            return redirect()->back()->with('error', 'Data pelamar tidak ditemukan.');
        }

        Skill::create([
            'id_pelamar' => $pelamar->id_pelamar,
            'nama_skill' => $request->nama_skill,
        ]);

        return redirect()->route('skills.index')->with('success', 'Skill berhasil ditambahkan.');
    }

    public function edit(Skill $skill)
    {
        $pelamar = Pelamar::where('id_user', Auth::id())->first();

        if ($skill->id_pelamar !== $pelamar->id_pelamar) {
            abort(403, 'Akses ditolak.');
        }

        return view('skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'nama_skill' => 'required|string|max:100',
        ]);

        $pelamar = Pelamar::where('id_user', Auth::id())->first();

        if ($skill->id_pelamar !== $pelamar->id_pelamar) {
            abort(403, 'Akses ditolak.');
        }

        $skill->update([
            'nama_skill' => $request->nama_skill,
        ]);

        return redirect()->route('skills.index')->with('success', 'Skill berhasil diperbarui.');
    }

    public function destroy(Skill $skill)
    {
        $pelamar = Pelamar::where('id_user', Auth::id())->first();

        if ($skill->id_pelamar !== $pelamar->id_pelamar) {
            abort(403, 'Akses ditolak.');
        }

        $skill->delete();

        return redirect()->route('skills.index')->with('success', 'Skill berhasil dihapus.');
    }
}
