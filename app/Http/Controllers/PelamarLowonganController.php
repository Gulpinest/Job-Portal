<?php

namespace App\Http\Controllers;

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
        return view('lowongans.show', compact('lowongan'));
    }
}