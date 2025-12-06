<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class AdminCompanyController extends Controller
{
    /**
     * Display a listing of all companies (both verified and unverified)
     */
    public function index(Request $request)
    {
        $query = Company::with('user');

        // Filter by verification status
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status === 'unverified') {
                $query->where('is_verified', false);
            }
        }

        // Search by company name or email
        if ($request->filled('search')) {
            $query->where('nama_perusahaan', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q) use ($request) {
                      $q->where('email', 'like', '%' . $request->search . '%');
                  });
        }

        $companies = $query->latest()->paginate(15);

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for verifying/rejecting a company
     */
    public function show(Company $company)
    {
        $company->load('user');
        return view('admin.companies.show', compact('company'));
    }

    /**
     * Verify a company registration
     */
    public function verify(Company $company)
    {
        $company->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        return redirect()->route('admin.companies.index')
                        ->with('success', 'Akun perusahaan ' . $company->nama_perusahaan . ' berhasil diverifikasi!');
    }

    /**
     * Reject a company registration
     */
    public function reject(Request $request, Company $company)
    {
        $request->validate([
            'alasan_penolakan' => 'nullable|string|max:500',
        ]);

        // Store rejection reason
        $company->update(['rejection_reason' => $request->alasan_penolakan]);

        // Delete the company and associated user
        $user = $company->user;
        $company->delete();
        $user->delete();

        return redirect()->route('admin.companies.index')
                        ->with('success', 'Akun perusahaan berhasil ditolak dan dihapus!');
    }
}
