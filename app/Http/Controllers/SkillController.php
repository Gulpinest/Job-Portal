<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    /**
     * Display all skills selected by logged-in pelamar
     */
    public function index()
    {
        $pelamar = Pelamar::where('id_user', Auth::id())->first();
        $skills = $pelamar ? $pelamar->skills()->select('skills.*')->orderByDesc('pelamar_skill.created_at')->paginate(12) : collect();

        return view('skills.index', compact('skills'));
    }

    /**
     * Show form to add existing skills to pelamar profile
     */
    public function create()
    {
        $pelamar = Pelamar::where('id_user', Auth::id())->first();

        // Get all master skills
        $allSkills = Skill::orderBy('nama_skill')->get();

        // Get skills already added by this pelamar
        $selectedSkillIds = $pelamar ? $pelamar->skills()->select('skills.id_skill')->pluck('skills.id_skill')->toArray() : [];

        return view('skills.create', compact('allSkills', 'selectedSkillIds'));
    }

    /**
     * Store selected skills to pelamar profile
     */
    public function store(Request $request)
    {
        $request->validate([
            'skills' => 'required|array|min:1',
            'skills.*.id_skill' => 'required|exists:skills,id_skill',
            'skills.*.level' => 'required|in:Beginner,Intermediate,Advanced,Expert',
            'skills.*.years_experience' => 'nullable|integer|min:0|max:70',
        ]);

        $pelamar = Pelamar::where('id_user', Auth::id())->firstOrFail();

        // Process each selected skill
        foreach ($request->skills as $skillData) {
            $skillId = $skillData['id_skill'];
            $level = $skillData['level'];
            $years = $skillData['years_experience'] ?? 0;

            // Use sync to avoid duplicates, or updateOrCreate
            $pelamar->skills()->syncWithoutDetaching([
                $skillId => [
                    'level' => $level,
                    'years_experience' => $years,
                ]
            ]);
        }

        return redirect()->route('skills.index')
                        ->with('success', 'Skill berhasil ditambahkan ke profil Anda!');
    }

    /**
     * Show form to edit skill level and experience
     */
    public function edit(Skill $skill)
    {
        $pelamar = Pelamar::where('id_user', Auth::id())->first();

        // Check if pelamar has this skill
        if (!$pelamar || !$pelamar->skills()->where('id_skill', $skill->id_skill)->exists()) {
            abort(403, 'Skill ini tidak ada di profil Anda.');
        }

        $pelamarSkill = $pelamar->skills()->find($skill->id_skill);

        return view('skills.edit', compact('skill', 'pelamarSkill'));
    }

    /**
     * Update skill level and experience for pelamar
     */
    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'level' => 'required|in:Beginner,Intermediate,Advanced,Expert',
            'years_experience' => 'nullable|integer|min:0|max:70',
        ]);

        $pelamar = Pelamar::where('id_user', Auth::id())->firstOrFail();

        // Check if pelamar has this skill
        if (!$pelamar->skills()->where('id_skill', $skill->id_skill)->exists()) {
            abort(403, 'Skill ini tidak ada di profil Anda.');
        }

        // Update the pivot table
        $pelamar->skills()->updateExistingPivot($skill->id_skill, [
            'level' => $request->level,
            'years_experience' => $request->years_experience ?? 0,
        ]);

        return redirect()->route('skills.index')
                        ->with('success', 'Skill berhasil diperbarui!');
    }

    /**
     * Remove a skill from pelamar profile
     */
    public function destroy(Skill $skill)
    {
        $pelamar = Pelamar::where('id_user', Auth::id())->firstOrFail();

        // Check if pelamar has this skill
        if (!$pelamar->skills()->where('id_skill', $skill->id_skill)->exists()) {
            abort(403, 'Skill ini tidak ada di profil Anda.');
        }

        // Detach the skill
        $pelamar->skills()->detach($skill->id_skill);

        return redirect()->route('skills.index')
                        ->with('success', 'Skill berhasil dihapus dari profil Anda!');
    }
}
