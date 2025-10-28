<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
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

        $existingLamaran = Lamaran::where('id_lowongan', $request->id_lowongan)
                                  ->where('id_pelamar', $user->pelamar->id_pelamar)
                                  ->exists();

        if ($existingLamaran) {
            return redirect()->back()->with('error', 'Anda sudah melamar lowongan ini.');
        }

        Lamaran::create([
            'id_lowongan' => $request->id_lowongan,
            'id_resume' => $request->id_resume,
            'id_pelamar' => $user->pelamar->id_pelamar,
            'cv'=> $request->id_resume,
            'status' => 'Diajukan',
        ]);

        return redirect()->route('lowongans.pelamar_index')->with('success', 'Lamaran Anda berhasil dikirim!');
    }
}