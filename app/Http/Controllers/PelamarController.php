<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelamar;
use Illuminate\Support\Facades\Auth;

class PelamarController extends Controller
{
    // Lihat profil pelamar
    public function show()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();

        // cari pelamar berdasarkan user yang login
        $pelamar = \App\Models\Pelamar::where('id_user', $userId)->first();

        // kalau belum ada, buat otomatis
        if (!$pelamar) {
            $pelamar = \App\Models\Pelamar::create([
                'id_user' => $userId,
                'nama_pelamar' => 'Belum diisi',
                'status_pekerjaan' => null,
                'no_telp' => null,
                'alamat' => null,
                'jenis_kelamin' => null,
                'tgl_lahir' => null,
            ]);
        }

        // Load skills
        $skills = $pelamar->skills;

        return view('pelamar.show', compact('pelamar', 'skills'));
    }

    // Form edit profil pelamar
    public function edit()
    {
        $pelamar = Pelamar::where('id_user', Auth::id())->firstOrFail();
        return view('pelamar.edit', compact('pelamar'));
    }

    // Update data profil pelamar
    public function update(Request $request)
    {
        $pelamar = Pelamar::where('id_user', Auth::id())->firstOrFail();

        $request->validate([
            'nama_pelamar' => 'required|string|max:255',
            'status_pekerjaan' => 'nullable|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tgl_lahir' => 'nullable|date',
        ]);

        $pelamar->update($request->all());

        return redirect()->route('pelamar.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    // Hapus akun pelamar
    public function destroy()
    {
        $pelamar = Pelamar::where('id_user', Auth::id())->firstOrFail();
        $pelamar->delete();

        Auth::logout();

        return redirect('/')->with('success', 'Akun Anda telah dihapus.');
    }
}
