<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelamarLowonganController extends Controller
{
    public function index(Request $request)
    {
        $pelamar = Auth::user()->pelamar;
        $pelamarSkills = $pelamar->skills->pluck('nama_skill')->toArray();

        $lowongans = Lowongan::with(['company', 'skills'])
            ->latest()
            ->when($request->filled('match') && $request->match === 'true', function ($query) use ($pelamarSkills) {
                $query->matchSkills($pelamarSkills);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->search($request->search);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->status($request->status);
            })
            ->paginate(9);

        return view('lowongans.pelamar_index', compact('lowongans'));
    }


    public function show(Lowongan $lowongan)
    {
        $lowongan->load('company', 'skills');
        $resumes = Auth::user()->pelamar->resumes;

        return view('lowongans.detail', compact('lowongan', 'resumes'));
    }

    public function lamaran_saya()
    {
        $user = Auth::user();

        $lamarans = Lamaran::where('id_pelamar', $user->pelamar->id_pelamar)
                           ->with(['lowongan.company', 'resume'])
                           ->latest()
                           ->get();

        return view('lowongans.lamaran_saya', compact('lamarans'));
    }
}
