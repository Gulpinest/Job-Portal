<?php

namespace App\Http\Controllers;

use App\Models\Lamaran; // Pastikan Model Lamaran sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LamaranController extends Controller
{
    /**
     * Menyimpan data lamaran ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_lowongan' => 'required|exists:lowongans,id_lowongan',
            'id_resume' => 'required|exists:resumes,id_resume',
        ]);

        $user = Auth::user();

        // 1. Cek apakah Pelamar sudah pernah melamar lowongan ini
        $existingLamaran = Lamaran::where('id_lowongan', $request->id_lowongan)
                                  ->where('id_user', $user->id)
                                  ->exists();

        if ($existingLamaran) {
            return redirect()->back()->with('error', 'Anda sudah melamar lowongan ini.');
        }

        // 2. Simpan Lamaran baru
        Lamaran::create([
            'id_lowongan' => $request->id_lowongan,
            'id_resume' => $request->id_resume,
            'id_user' => $user->id,
            'status' => 'Diajukan', // Status awal lamaran
        ]);

        return redirect()->route('lowongans.pelamar_index')->with('success', 'Lamaran Anda berhasil dikirim! Kami akan memberitahu Anda jika ada pembaruan.');
    }
}