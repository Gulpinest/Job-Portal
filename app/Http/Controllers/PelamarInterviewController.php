<?php

namespace App\Http\Controllers;

use App\Models\InterviewSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PelamarInterviewController extends Controller
{
    /**
     * Display all interview schedules for logged-in pelamar
     */
    public function index(Request $request)
    {
        $pelamar = Auth::user()->pelamar;

        // Get lamarans with accepted status and their interview schedules
        $interviews = InterviewSchedule::whereHas('lowongan.lamarans', function ($query) use ($pelamar) {
            $query->where('id_pelamar', $pelamar->id_pelamar)
                  ->where('status_ajuan', 'Accepted');
        })
        ->with(['lowongan.company', 'lowongan.lamarans' => function ($q) use ($pelamar) {
            $q->where('id_pelamar', $pelamar->id_pelamar)->where('status_ajuan', 'Accepted');
        }])
        ->when($request->filled('search'), function ($query) use ($request) {
            $searchTerm = $request->search;
            $query->whereHas('lowongan.company', function ($q) use ($searchTerm) {
                $q->where('nama_perusahaan', 'like', '%' . $searchTerm . '%');
            })
            ->orWhereHas('lowongan', function ($q) use ($searchTerm) {
                $q->where('judul', 'like', '%' . $searchTerm . '%');
            });
        })
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status', $request->status);
        })
        ->latest('waktu_jadwal')
        ->paginate(10);

        // Statistics
        $totalInterviews = InterviewSchedule::whereHas('lowongan.lamarans', function ($query) use ($pelamar) {
            $query->where('id_pelamar', $pelamar->id_pelamar)
                  ->where('status_ajuan', 'Accepted');
        })->count();

        $upcomingInterviews = InterviewSchedule::whereHas('lowongan.lamarans', function ($query) use ($pelamar) {
            $query->where('id_pelamar', $pelamar->id_pelamar)
                  ->where('status_ajuan', 'Accepted');
        })
        ->where('waktu_jadwal', '>=', now())
        ->where('status', 'Scheduled')
        ->count();

        $completedInterviews = InterviewSchedule::whereHas('lowongan.lamarans', function ($query) use ($pelamar) {
            $query->where('id_pelamar', $pelamar->id_pelamar)
                  ->where('status_ajuan', 'Accepted');
        })
        ->where('status', 'Completed')
        ->count();

        $cancelledInterviews = InterviewSchedule::whereHas('lowongan.lamarans', function ($query) use ($pelamar) {
            $query->where('id_pelamar', $pelamar->id_pelamar)
                  ->where('status_ajuan', 'Accepted');
        })
        ->where('status', 'Cancelled')
        ->count();

        return view('pelamar.interviews.index', compact(
            'interviews',
            'totalInterviews',
            'upcomingInterviews',
            'completedInterviews',
            'cancelledInterviews'
        ));
    }

    /**
     * Show interview detail
     */
    public function show($id)
    {
        $interview = InterviewSchedule::where('id', $id)
            ->with(['lowongan.company', 'lowongan.lamarans' => function ($q) {
                $q->where('id_pelamar', Auth::user()->pelamar->id_pelamar)
                  ->where('status_ajuan', 'Accepted');
            }])
            ->firstOrFail();
        // dd($interview);
        return view('pelamar.interviews.show', compact('interview'));
    }
}
