<?php

namespace App\Http\Controllers;

use App\Models\InterviewSchedule;
use App\Models\Lamaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewScheduleController extends Controller
{
    /**
     * Menampilkan semua jadwal interview untuk company yang login.
     */
    public function index()
    {
        // Mengambil data company dari user yang sedang login.
        $company = Auth::user()->company;

        // Mengambil semua jadwal interview milik company tersebut,
        // beserta data relasi 'lowongan' dan 'lamaran' (dengan data 'pelamar'-nya).
        $schedules = InterviewSchedule::where('id_company', $company->id_company)
                                      ->with(['lowongan', 'lamaran.pelamar']) // Eager Loading
                                      ->latest()
                                      ->get();

        return view('interview-schedules.index', compact('schedules'));
    }

    /**
     * Menampilkan form untuk membuat jadwal interview.
     */
    public function create()
    {
        $company = Auth::user()->company;

        // Mengambil daftar lamaran yang masuk ke company ini untuk ditampilkan di form.
        $lamarans = Lamaran::whereHas('lowongan', function($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })->with('pelamar')->get();
        
        return view('interview-schedules.create', compact('lamarans'));
    }

    /**
     * Menyimpan jadwal interview baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_lamaran' => 'required|exists:lamarans,id_lamaran',
            'tempat' => 'required|string|max:255',
        ]);

        $company = Auth::user()->company;
        $lamaran = Lamaran::with('lowongan')->find($request->id_lamaran);

        // Keamanan: Memastikan lamaran yang dipilih adalah milik company yang login.
        if ($lamaran->lowongan->id_company !== $company->id_company) {
            abort(403, 'Akses Ditolak');
        }

        InterviewSchedule::create([
            'id_lamaran' => $lamaran->id_lamaran,
            'id_lowongan' => $lamaran->id_lowongan,
            'id_company' => $company->id_company,
            'tempat' => $request->tempat,
        ]);

        return redirect()->route('interview-schedules.index')->with('success', 'Jadwal interview berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit jadwal interview.
     */
    public function edit(InterviewSchedule $interviewSchedule)
    {
        // Keamanan: Pastikan company yang login adalah pemilik jadwal ini.
        if ($interviewSchedule->id_company !== Auth::user()->company->id_company) {
            abort(403, 'Akses Ditolak');
        }

        $company = Auth::user()->company;
        // Ambil daftar lamaran untuk dropdown, sama seperti di method create().
        $lamarans = Lamaran::whereHas('lowongan', function($query) use ($company) {
            $query->where('id_company', $company->id_company);
        })->with('pelamar')->get();

        return view('interview-schedules.edit', compact('interviewSchedule', 'lamarans'));
    }

    /**
     * Mengupdate data jadwal interview di database.
     */
    public function update(Request $request, InterviewSchedule $interviewSchedule)
    {
        $request->validate([
            'id_lamaran' => 'required|exists:lamarans,id_lamaran',
            'tempat' => 'required|string|max:255',
        ]);

        $company = Auth::user()->company;
        $lamaran = Lamaran::with('lowongan')->find($request->id_lamaran);
        
        // Keamanan ganda:
        // 1. Cek kepemilikan jadwal yang akan di-update.
        // 2. Cek kepemilikan lamaran baru yang dipilih.
        if ($interviewSchedule->id_company !== $company->id_company || $lamaran->lowongan->id_company !== $company->id_company) {
            abort(403, 'Akses Ditolak');
        }

        $interviewSchedule->update([
            'id_lamaran' => $lamaran->id_lamaran,
            'id_lowongan' => $lamaran->id_lowongan,
            'id_company' => $company->id_company,
            'tempat' => $request->tempat,
        ]);

        return redirect()->route('interview-schedules.index')->with('success', 'Jadwal interview berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal interview dari database.
     */
    public function destroy(InterviewSchedule $interviewSchedule)
    {
        // Keamanan: Pastikan company yang login adalah pemilik jadwal ini.
        if ($interviewSchedule->id_company !== Auth::user()->company->id_company) {
            abort(403, 'Akses Ditolak');
        }
        
        $interviewSchedule->delete();

        return redirect()->route('interview-schedules.index')->with('success', 'Jadwal interview berhasil dihapus.');
    }
}