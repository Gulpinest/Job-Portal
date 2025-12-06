<?php

namespace App\Http\Controllers;

use App\Models\InterviewSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ->latest('tanggal_interview')
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
        ->where('tanggal_interview', '>=', now())
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
    public function show(InterviewSchedule $interview)
    {
        $pelamar = Auth::user()->pelamar;

        // Verify pelamar is in the accepted lamarans for this interview's lowongan
        $accepted = $interview->lowongan->lamarans()
            ->where('id_pelamar', $pelamar->id_pelamar)
            ->where('status_ajuan', 'Accepted')
            ->exists();

        if (!$accepted) {
            abort(403);
        }

        $interview->load(['lowongan.company']);

        return view('pelamar.interviews.show', compact('interview'));
    }

    /**
     * Mark interview as attended/completed by pelamar
     */
    public function markAttended(InterviewSchedule $interview)
    {
        $pelamar = Auth::user()->pelamar;

        // Verify ownership
        $accepted = $interview->lowongan->lamarans()
            ->where('id_pelamar', $pelamar->id_pelamar)
            ->where('status_ajuan', 'Accepted')
            ->exists();

        if (!$accepted) {
            abort(403);
        }

        if ($interview->status === 'Scheduled') {
            $interview->update(['status' => 'Completed']);
        }

        return redirect()->back()->with('success', 'Wawancara ditandai sebagai selesai!');
    }

    /**
     * Cancel/Decline interview by pelamar
     */
    public function decline(Request $request, InterviewSchedule $interview)
    {
        $pelamar = Auth::user()->pelamar;

        // Verify ownership
        $accepted = $interview->lowongan->lamarans()
            ->where('id_pelamar', $pelamar->id_pelamar)
            ->where('status_ajuan', 'Accepted')
            ->exists();

        if (!$accepted) {
            abort(403);
        }

        $request->validate([
            'alasan_pembatalan' => 'nullable|string|max:500',
        ]);

        $interview->update(['status' => 'Cancelled']);

        return redirect()->route('pelamar.interviews.index')
                        ->with('success', 'Wawancara berhasil dibatalkan!');
    }
}
