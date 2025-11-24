<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelamarLowonganController extends Controller
{
    /**
     * Display all job listings for pelamar (job seeker)
     * with search and filtering capabilities
     */
    public function index(Request $request)
    {
        $query = Lowongan::with('company')->where('status', 'Open');

        // Search by keyword (judul, posisi, keterampilan)
        if ($request->filled('search_keyword')) {
            $searchKeyword = $request->search_keyword;
            $query->where(function ($q) use ($searchKeyword) {
                $q->where('judul', 'like', "%{$searchKeyword}%")
                  ->orWhere('posisi', 'like', "%{$searchKeyword}%")
                  ->orWhere('keterampilan', 'like', "%{$searchKeyword}%");
            });
        }

        // Search by location
        if ($request->filled('search_location')) {
            $searchLocation = $request->search_location;
            $query->where('lokasi_kantor', 'like', "%{$searchLocation}%");
        }

        // Filter by job type (array)
        if ($request->filled('job_type')) {
            $jobTypes = $request->input('job_type');
            $query->whereIn('tipe_kerja', $jobTypes);
        }

        // Filter by posted within date range
        if ($request->filled('posted_within') && $request->posted_within !== 'Any') {
            $postedWithin = $request->posted_within;

            $daysAgo = match($postedWithin) {
                'Today' => 1,
                'Last 2 days' => 2,
                'Last 5 days' => 5,
                'Last 10 days' => 10,
                default => 999,
            };

            $query->where('created_at', '>=', now()->subDays($daysAgo));
        }

        // Sorting
        $sort = $request->input('sort', 'Terbaru');
        $query = match($sort) {
            'Gaji Tertinggi' => $query->orderBy('gaji', 'desc'),
            'Terbaru' => $query->latest(),
            default => $query->latest(),
        };

        // Get results
        $lowongans = $query->get();

        return view('lowongans.pelamar_index', compact('lowongans'));
    }

    /**
     * Show job detail and application form for pelamar
     */
    public function show(Lowongan $lowongan)
    {
        $lowongan->load('company');
        $resumes = Auth::user()->pelamar->resumes;

        return view('lowongans.detail', compact('lowongan', 'resumes'));
    }

    /**
     * Show list of applications submitted by pelamar
     */
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
