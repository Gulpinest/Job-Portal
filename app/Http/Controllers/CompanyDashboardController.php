<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\Lamaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyDashboardController extends Controller
{
    public function dashboard()
    {
        $company = Auth::user()->company()->with('package')->first();

        $is_active = $company->subscription_ends_at && $company->subscription_ends_at->isFuture();
        // Statistics
        $totalLowongans = Lowongan::where('id_company', $company->id_company)->count();
        $activeLowongans = Lowongan::where('id_company', $company->id_company)
            ->where('status', 'Open')
            ->count();
        $closedLowongans = Lowongan::where('id_company', $company->id_company)
            ->where('status', 'Closed')
            ->count();

        // Get all lamarans for this company's lowongans
        $totalPelamar = Lamaran::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })->count();

        $pendingPelamar = Lamaran::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })->where('status_ajuan', 'Pending')->count();

        // Recent lowongans
        $recentLowongans = Lowongan::where('id_company', $company->id_company)
            ->with('skills')
            ->withCount('lamarans')
            ->latest()
            ->limit(5)
            ->get();

        // Recent lamarans
        $recentLamarans = Lamaran::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })
        ->with(['pelamar', 'lowongan'])
        ->latest()
        ->limit(5)
        ->get();

        return view('company.dashboard', compact(
            'company',
            'is_active',
            'totalLowongans',
            'activeLowongans',
            'closedLowongans',
            'totalPelamar',
            'pendingPelamar',
            'recentLowongans',
            'recentLamarans'
        ));
    }
}
