<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class AdminSkillController extends Controller
{
    /**
     * Display a listing of all master skills
     */
    public function index()
    {
        $skills = Skill::latest()->paginate(15);
        return view('admin.skills.index', compact('skills'));
    }

    /**
     * Show the form for creating a new master skill
     */
    public function create()
    {
        return view('admin.skills.create');
    }

    /**
     * Store a newly created master skill in database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_skill' => 'required|string|max:100|unique:skills,nama_skill',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        Skill::create([
            'nama_skill' => $request->nama_skill,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.skills.index')
                        ->with('success', 'Skill berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified master skill
     */
    public function edit(Skill $skill)
    {
        return view('admin.skills.edit', compact('skill'));
    }

    /**
     * Update the specified master skill in database
     */
    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'nama_skill' => 'required|string|max:100|unique:skills,nama_skill,' . $skill->id_skill . ',id_skill',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $skill->update([
            'nama_skill' => $request->nama_skill,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.skills.index')
                        ->with('success', 'Skill berhasil diperbarui!');
    }

    /**
     * Delete the specified master skill from database
     */
    public function destroy(Skill $skill)
    {
        $skill->delete();

        return redirect()->route('admin.skills.index')
                        ->with('success', 'Skill berhasil dihapus!');
    }
}
