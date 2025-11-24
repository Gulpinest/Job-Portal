<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\Resume;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LamaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_lowongan' => 'required|exists:lowongans,id_lowongan',
            'id_resume' => 'required|exists:resumes,id_resume',
        ]);

        $user = Auth::user();
        $pelamarId = $user->pelamar->id_pelamar;

        // Verify resume belongs to logged-in pelamar
        $resume = Resume::where('id_resume', $request->id_resume)
                        ->where('id_pelamar', $pelamarId)
                        ->firstOrFail();

        // Check if already applied to this job
        $existingLamaran = Lamaran::where('id_lowongan', $request->id_lowongan)
                                  ->where('id_pelamar', $pelamarId)
                                  ->exists();

        if ($existingLamaran) {
            return redirect()->back()->with('error', 'Anda sudah melamar lowongan ini.');
        }

        // Check if job listing is still open
        $lowongan = Lowongan::findOrFail($request->id_lowongan);
        if ($lowongan->status !== 'Open') {
            return redirect()->back()->with('error', 'Lowongan ini sudah ditutup.');
        }

        Lamaran::create([
            'id_lowongan' => $request->id_lowongan,
            'id_resume' => $request->id_resume,
            'id_pelamar' => $pelamarId,
            'cv' => $request->id_resume,
            'status' => 'Diajukan',
        ]);

        return redirect()->route('lowongans.pelamar_index')->with('success', 'Lamaran Anda berhasil dikirim!');
    }
}