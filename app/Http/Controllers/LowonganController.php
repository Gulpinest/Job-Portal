<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LowonganController extends Controller
{
    /**
     * Menampilkan semua lowongan dari company yang sedang login.
     */
    public function index()
    {
        $company = Auth::user()->company;
        
        // Ambil semua lowongan milik company tersebut
        $lowongans = Lowongan::where('id_company', $company->id_company)->latest()->get();
        
        return view('lowongans.index', compact('lowongans'));
    }

    public function create()
    {
        return view('lowongans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:Open,Closed',
        ]);

        $company = Auth::user()->company;

        Lowongan::create([
            'id_company' => $company->id_company,
            'judul' => $request->judul,
            'posisi' => $request->posisi,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return redirect()->route('lowongans.index')->with('success', 'Lowongan baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit lowongan.
     */
    public function edit(Lowongan $lowongan)
    {
        // Keamanan: Pastikan company hanya bisa edit lowongannya sendiri
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403, 'AKSES DITOLAK');
        }

        return view('lowongans.edit', compact('lowongan'));
    }

    /**
     * Memperbarui data lowongan di database.
     */
    public function update(Request $request, Lowongan $lowongan)
    {
        // Keamanan: Pastikan company hanya bisa update lowongannya sendiri
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403, 'AKSES DITOLAK');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:Open,Closed',
        ]);

        $lowongan->update($request->all());

        return redirect()->route('lowongans.index')->with('success', 'Lowongan berhasil diperbarui!');
    }

    /**
     * Menghapus lowongan dari database.
     */
    public function destroy(Lowongan $lowongan)
    {
        // Keamanan: Pastikan company hanya bisa hapus lowongannya sendiri
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403, 'AKSES DITOLAK');
        }

        $lowongan->delete();

        return redirect()->route('lowongans.index')->with('success', 'Lowongan berhasil dihapus!');
    }
}