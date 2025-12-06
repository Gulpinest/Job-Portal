<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyLamaranController extends Controller
{
    /**
     * Display list of all applications for company's job listings
     */
    public function index(Request $request)
    {
        $company = Auth::user()->company;

        // Query all lamarans for this company's lowongans
        $lamaransQuery = Lamaran::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })
        ->with(['pelamar.user', 'lowongan', 'resume'])
        ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $lamaransQuery->where('status_ajuan', $request->status);
        }

        // Filter by lowongan
        if ($request->filled('id_lowongan')) {
            $lamaransQuery->where('id_lowongan', $request->id_lowongan);
        }

        // Search by pelamar name or email
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $lamaransQuery->whereHas('pelamar.user', function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $lamarans = $lamaransQuery->paginate(10);

        // Get company's lowongans for filter dropdown
        $lowongans = Lowongan::where('id_company', $company->id_company)
                             ->select('id_lowongan', 'judul')
                             ->latest()
                             ->get();

        // Statistics
        $totalLamarans = Lamaran::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })->count();

        $pendingLamarans = Lamaran::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })->where('status_ajuan', 'Pending')->count();

        $acceptedLamarans = Lamaran::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })->where('status_ajuan', 'Accepted')->count();

        $rejectedLamarans = Lamaran::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })->where('status_ajuan', 'Rejected')->count();

        return view('company.lamarans.index', compact(
            'lamarans',
            'lowongans',
            'totalLamarans',
            'pendingLamarans',
            'acceptedLamarans',
            'rejectedLamarans'
        ));
    }

    /**
     * Show single application detail
     */
    public function show(Lamaran $lamaran)
    {
        $company = Auth::user()->company;

        // Verify this lamaran belongs to company's lowongan
        if ($lamaran->lowongan->id_company !== $company->id_company) {
            abort(403, 'Anda tidak memiliki akses ke lamaran ini');
        }

        $lamaran->load(['pelamar.user', 'lowongan', 'resume']);

        return view('company.lamarans.show', compact('lamaran'));
    }

    /**
     * Accept application
     */
    public function accept(Lamaran $lamaran)
    {
        $company = Auth::user()->company;

        // Verify ownership
        if ($lamaran->lowongan->id_company !== $company->id_company) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        $lamaran->update(['status_ajuan' => 'Accepted']);

        return redirect()->back()->with('success', 'Lamaran berhasil diterima!');
    }

    /**
     * Reject application with reason
     */
    public function reject(Request $request, Lamaran $lamaran)
    {
        $request->validate([
            'alasan_penolakan' => 'nullable|string|max:500',
        ]);

        $company = Auth::user()->company;

        // Verify ownership
        if ($lamaran->lowongan->id_company !== $company->id_company) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        $lamaran->update([
            'status_ajuan' => 'Rejected',
            'rejection_reason' => $request->alasan_penolakan,
        ]);

        return redirect()->back()->with('success', 'Lamaran berhasil ditolak!');
    }
}
