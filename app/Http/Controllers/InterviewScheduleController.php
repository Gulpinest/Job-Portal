<?php

namespace App\Http\Controllers;

use App\Models\InterviewSchedule;
use App\Models\Lowongan; // Import model Lowongan
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
        // beserta data relasi 'lowongan'. Relasi ke 'lamaran' tidak ada di migrasi,
        // jadi kita hapus eager loading-nya.
        $schedules = InterviewSchedule::where('id_company', $company->id_company)
                                      ->with('lowongan') // Eager Loading
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

        // Mengambil daftar lowongan milik company ini untuk ditampilkan di form.
        $lowongans = Lowongan::where('id_company', $company->id_company)->get();
        
        return view('interview-schedules.create', compact('lowongans'));
    }

    /**
     * Menyimpan jadwal interview baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_lowongan' => 'required|exists:lowongans,id_lowongan',
            'type' => 'required|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'waktu_jadwal' => 'required|date_format:Y-m-d\TH:i',
            'catatan' => 'nullable|string',
        ]);

        $company = Auth::user()->company;
        $lowongan = Lowongan::find($request->id_lowongan);

        // Keamanan: Memastikan lowongan yang dipilih adalah milik company yang login.
        if ($lowongan->id_company !== $company->id_company) {
            abort(403, 'Akses Ditolak');
        }

        InterviewSchedule::create([
            'id_lowongan' => $request->id_lowongan,
            'id_company' => $company->id_company, // Asumsi ada relasi ke company
            'type' => $request->type,
            'tempat' => $request->tempat,
            'waktu_jadwal' => $request->waktu_jadwal,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('interview-schedules.index')->with('success', 'Jadwal interview berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit jadwal interview.
     */
    public function edit(InterviewSchedule $interviewSchedule)
    {
        $company = Auth::user()->company;

        // Keamanan: Pastikan company yang login adalah pemilik jadwal ini.
        if ($interviewSchedule->id_company !== $company->id_company) {
            abort(403, 'Akses Ditolak');
        }

        // Ambil daftar lowongan untuk dropdown, sama seperti di method create().
        $lowongans = Lowongan::where('id_company', $company->id_company)->get();

        return view('interview-schedules.edit', compact('interviewSchedule', 'lowongans'));
    }

    /**
     * Mengupdate data jadwal interview di database.
     */
    public function update(Request $request, InterviewSchedule $interviewSchedule)
    {
        $request->validate([
            'id_lowongan' => 'required|exists:lowongans,id_lowongan',
            'type' => 'required|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'waktu_jadwal' => 'required|date_format:Y-m-d\TH:i',
            'catatan' => 'nullable|string',
        ]);

        $company = Auth::user()->company;
        $lowongan = Lowongan::find($request->id_lowongan);
        
        // Keamanan ganda:
        // 1. Cek kepemilikan jadwal yang akan di-update.
        // 2. Cek kepemilikan lowongan baru yang dipilih.
        if ($interviewSchedule->id_company !== $company->id_company || $lowongan->id_company !== $company->id_company) {
            abort(403, 'Akses Ditolak');
        }

        $interviewSchedule->update([
            'id_lowongan' => $request->id_lowongan,
            'type' => $request->type,
            'tempat' => $request->tempat,
            'waktu_jadwal' => $request->waktu_jadwal,
            'catatan' => $request->catatan,
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