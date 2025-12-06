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

        // Check if pelamar already applied to this job
        $isAlreadyApplied = Lamaran::where('id_pelamar', Auth::user()->pelamar->id_pelamar)
                                    ->where('id_lowongan', $lowongan->id_lowongan)
                                    ->exists();

        return view('lowongans.detail', compact('lowongan', 'resumes', 'isAlreadyApplied'));
    }

    /**
     * Show list of applications submitted by pelamar
     */
    public function lamaran_saya(Request $request)
    {
        $user = Auth::user();
        $pelamarId = $user->pelamar->id_pelamar;

        // Query lamarans dengan eager loading
        $lamaransQuery = Lamaran::where('id_pelamar', $pelamarId)
                                 ->with(['lowongan.company', 'resume'])
                                 ->latest();

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $lamaransQuery->where('status_ajuan', $request->status);
        }

        // Search berdasarkan nama perusahaan atau posisi
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $lamaransQuery->whereHas('lowongan', function ($query) use ($searchTerm) {
                $query->where('judul', 'like', "%{$searchTerm}%")
                      ->orWhere('posisi', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('lowongan.company', function ($query) use ($searchTerm) {
                $query->where('nama_perusahaan', 'like', "%{$searchTerm}%");
            });
        }

        // Hitung statistik
        $totalLamarans = Lamaran::where('id_pelamar', $pelamarId)->count();
        $diterimaaCount = Lamaran::where('id_pelamar', $pelamarId)->where('status_ajuan', 'Accepted')->count();
        $ditolakCount = Lamaran::where('id_pelamar', $pelamarId)->where('status_ajuan', 'Rejected')->count();
        $diproseswCount = Lamaran::where('id_pelamar', $pelamarId)->where('status_ajuan', 'Pending')->count();

        // Pagination
        $lamarans = $lamaransQuery->paginate(8);

        return view('lowongans.lamaran_saya', compact('lamarans', 'totalLamarans', 'diterimaaCount', 'ditolakCount', 'diproseswCount'));
    }
}
