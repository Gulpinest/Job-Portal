<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelamarLowonganController extends Controller
{
    public function index()
    {
        $lowongans = Lowongan::with('company')->latest()->get(); 
        
        return view('lowongans.pelamar_index', compact('lowongans'));
    }

    public function show(Lowongan $lowongan)
    {
        $lowongan->load('company');
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