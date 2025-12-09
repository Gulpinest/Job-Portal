<?php

namespace App\Http\Controllers;

use App\Models\InterviewSchedule;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewScheduleController extends Controller
{
    /**
     * Display list of interview schedules for company
     */
    public function index(Request $request)
    {
        $company = Auth::user()->company;

        $schedules = InterviewSchedule::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })
        ->with(['lowongan'])
        ->when($request->filled('search'), function ($query) use ($request) {
            $searchTerm = $request->search;
            $query->whereHas('lowongan', function ($q) use ($searchTerm) {
                $q->where('judul', 'like', "%{$searchTerm}%");
            });
        })
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status', $request->status);
        })
        ->latest('waktu_jadwal')
        ->paginate(10);

        // Statistics
        $totalSchedules = InterviewSchedule::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })->count();

        $upcomingSchedules = InterviewSchedule::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })
        ->where('waktu_jadwal', '>=', now())
        ->where('status', 'Scheduled')
        ->count();

        $completedSchedules = InterviewSchedule::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })
        ->where('status', 'Completed')
        ->count();

        $cancelledSchedules = InterviewSchedule::whereHas('lowongan', function ($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })
        ->where('status', 'Cancelled')
        ->count();

        return view('company.interviews.index', compact(
            'schedules',
            'totalSchedules',
            'upcomingSchedules',
            'completedSchedules',
            'cancelledSchedules'
        ));
    }

    /**
     * Show create interview form for specific lowongan
     */
    public function create(Request $request, Lowongan $lowongan)
    {
        $company = Auth::user()->company;

        // Verify ownership
        if ($lowongan->id_company !== $company->id_company) {
            abort(403);
        }

        // Check if interview already scheduled for this lowongan
        if ($lowongan->interviewSchedule) {
            return redirect()->route('lowongans.show', $lowongan)
                            ->with('info', 'Wawancara sudah dijadwalkan untuk lowongan ini.');
        }

        // Get accepted applicants for this lowongan
        $acceptedApplicants = $lowongan->lamarans()
            ->where('status_ajuan', 'Accepted')
            ->with(['pelamar.user'])
            ->get();

        return view('company.interviews.create', compact('lowongan', 'acceptedApplicants'));
    }

    /**
     * Store interview schedule for lowongan
     */
    public function store(Request $request, Lowongan $lowongan)
    {
        $company = Auth::user()->company;

        // Verify ownership
        if ($lowongan->id_company !== $company->id_company) {
            abort(403);
        }

        $validated = $request->validate([
            'waktu_jadwal' => 'required|date_format:Y-m-d\TH:i|after:now',
            'lokasi' => 'required|string|max:500',
            'type' => 'required|in:Online,Offline',
            'catatan' => 'nullable|string|max:1000',
        ]);

        // Check if interview already exists
        if ($lowongan->interviewSchedule) {
            return redirect()->back()->with('error', 'Wawancara sudah dijadwalkan untuk lowongan ini.');
        }

        InterviewSchedule::create([
            'id_lowongan' => $lowongan->id_lowongan,
            'waktu_jadwal' => $validated['waktu_jadwal'],
            'lokasi' => $validated['lokasi'],
            'type' => $validated['type'],
            'catatan' => $validated['catatan'],
            'status' => 'Scheduled',
        ]);

        return redirect()->route('lowongans.show', $lowongan)
                        ->with('success', 'Wawancara berhasil dijadwalkan untuk semua pelamar yang diterima!');
    }

    /**
     * Show interview detail
     */
    public function show(InterviewSchedule $interviewSchedule)
    {
        $company = Auth::user()->company;

        // Verify ownership
        if ($interviewSchedule->lowongan->id_company !== $company->id_company) {
            abort(403);
        }

        $interviewSchedule->load(['lowongan', 'lowongan.lamarans' => function ($q) {
            $q->where('status_ajuan', 'Accepted');
        }]);

        return view('company.interviews.show', compact('interviewSchedule'));
    }

    /**
     * Show edit interview form
     */
    public function edit(InterviewSchedule $interviewSchedule)
    {
        $company = Auth::user()->company;

        // Verify ownership
        if ($interviewSchedule->lowongan->id_company !== $company->id_company) {
            abort(403);
        }

        $interviewSchedule->load('lowongan');

        return view('company.interviews.edit', compact('interviewSchedule'));
    }

    /**
     * Update interview schedule
     */
    public function update(Request $request, InterviewSchedule $interviewSchedule)
    {
        $company = Auth::user()->company;

        // Verify ownership
        if ($interviewSchedule->lowongan->id_company !== $company->id_company) {
            abort(403);
        }

        $validated = $request->validate([
            'tanggal_interview' => 'required|date|after:now',
            'jam_interview' => 'required|date_format:H:i',
            'lokasi' => 'required|string|max:500',
            'tipe' => 'required|in:Online,Offline',
            'catatan' => 'nullable|string|max:1000',
        ]);

        // Combine date and time
        $dateTime = $validated['tanggal_interview'] . ' ' . $validated['jam_interview'];

        $interviewSchedule->update([
            'tanggal_interview' => $dateTime,
            'lokasi' => $validated['lokasi'],
            'tipe' => $validated['tipe'],
            'catatan' => $validated['catatan'],
        ]);

        return redirect()->route('interview-schedules.show', $interviewSchedule)
                        ->with('success', 'Wawancara berhasil diperbarui!');
    }

    /**
     * Cancel/Delete interview
     */
    public function destroy(InterviewSchedule $interviewSchedule)
    {
        $company = Auth::user()->company;

        // Verify ownership
        if ($interviewSchedule->lowongan->id_company !== $company->id_company) {
            abort(403);
        }

        $interviewSchedule->delete();

        return redirect()->route('interview-schedules.index')
                        ->with('success', 'Wawancara berhasil dibatalkan!');
    }

    /**
     * Mark interview as completed
     */
    public function markCompleted(InterviewSchedule $interviewSchedule)
    {
        $company = Auth::user()->company;

        // Verify ownership
        if ($interviewSchedule->lowongan->id_company !== $company->id_company) {
            abort(403);
        }

        $interviewSchedule->update(['status' => 'Completed']);

        return redirect()->back()->with('success', 'Status wawancara diperbarui menjadi Selesai!');
    }
}
