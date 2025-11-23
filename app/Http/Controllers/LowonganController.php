<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LowonganController extends Controller
{
    /**
     * Menampilkan daftar lowongan milik perusahaan yang sedang login (lowongans.index).
     */
    public function index()
    {
        // Asumsi user yang login memiliki relasi company
        $companyId = Auth::user()->company->id_company;

        $lowongans = Lowongan::where('id_company', $companyId)
                             ->latest()
                             ->get();

        return view('lowongans.index', compact('lowongans'));
    }

    /**
     * Menampilkan form untuk membuat lowongan baru (lowongans.create).
     */
    public function create()
    {
        return view('lowongans.create');
    }

    /**
     * Menyimpan data lowongan baru ke dalam database (lowongans.store).
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi_kantor' => 'nullable|string|max:255',
            'gaji' => 'nullable|string|max:255',
            'keterampilan' => 'nullable|string|max:500',
            'deskripsi' => 'nullable|string',
            'status' => ['required', Rule::in(['Open', 'Closed'])],
            // --- VALIDASI FIELD BARU DARI FORM ---
            'tipe_kerja' => ['required', 'string', Rule::in(['Full Time', 'Part Time', 'Remote', 'Freelance', 'Contract'])],
        ]);

        $companyId = Auth::user()->company->id_company;

        Lowongan::create([
            'id_company' => $companyId,
            'judul' => $request->judul,
            'posisi' => $request->posisi,
            'lokasi_kantor' => $request->lokasi_kantor,
            'gaji' => $request->gaji,
            'keterampilan' => $request->keterampilan,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            // --- SIMPAN FIELD BARU ---
            'tipe_kerja' => $request->tipe_kerja,
        ]);

        return redirect()->route('lowongans.index')
                         ->with('success', 'Lowongan baru berhasil dibuat!');
    }

    /**
     * Menampilkan form untuk mengedit lowongan (lowongans.edit).
     */
    public function edit(Lowongan $lowongan)
    {
        // Keamanan: Pastikan lowongan ini milik perusahaan yang sedang login.
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403, 'Akses Ditolak.');
        }

        return view('lowongans.edit', compact('lowongan'));
    }

    /**
     * Memperbarui data lowongan (lowongans.update).
     */
    public function update(Request $request, Lowongan $lowongan)
    {
        // Keamanan: Pastikan yang mau update adalah pemilik lowongan.
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi_kantor' => 'nullable|string|max:255',
            'gaji' => 'nullable|string|max:255',
            'keterampilan' => 'nullable|string|max:500',
            'deskripsi' => 'nullable|string',
            'status' => ['required', Rule::in(['Open', 'Closed'])],
            // --- VALIDASI FIELD BARU DARI FORM ---
            'tipe_kerja' => ['required', 'string', Rule::in(['Full Time', 'Part Time', 'Remote', 'Freelance', 'Contract'])],
        ]);

        $lowongan->update($request->all());

        return redirect()->route('lowongans.index')
                         ->with('success', 'Lowongan berhasil diperbarui!');
    }

    /**
     * Menghapus lowongan (lowongans.destroy).
     */
    public function destroy(Lowongan $lowongan)
    {
        // Keamanan: Pastikan yang mau hapus adalah pemilik lowongan.
        if ($lowongan->id_company !== Auth::user()->company->id_company) {
            abort(403);
        }

        $lowongan->delete();

        return redirect()->route('lowongans.index')
                         ->with('success', 'Lowongan berhasil dihapus!');
    }


    // --- METODE UNTUK PELAMAR (pelamar_index) ---
    /**
     * Menampilkan daftar lowongan untuk Pelamar dengan fitur filter dan pencarian.
     */
    public function pelamar_index(Request $request)
    {
        // 1. Inisialisasi query dengan lowongan yang statusnya 'Open'
        $lowongans = Lowongan::where('status', 'Open')->with('company');

        // 2. LOGIKA PENCARIAN (Search Bar di bagian atas)
        $searchKeyword = $request->input('search_keyword');
        $searchLocation = $request->input('search_location');

        if ($searchKeyword) {
            $lowongans->where(function ($query) use ($searchKeyword) {
                $query->where('judul', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('posisi', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('keterampilan', 'like', '%' . $searchKeyword . '%');
            });
        }
        
        if ($searchLocation) {
            $lowongans->where('lokasi_kantor', 'like', '%' . $searchLocation . '%');
        }

        // 3. LOGIKA FILTER SIDEBAR
        
        // Filter Job Type
        $jobTypes = $request->input('job_type', []);
        if (!empty($jobTypes)) {
            $lowongans->whereIn('tipe_kerja', $jobTypes); // Menggunakan tipe_kerja untuk filter
        }

        // Filter Experience (misalnya: 2-3 Years)
        $experience = $request->input('experience');
        if ($experience) {
            // Logika filter berdasarkan pengalaman
        }

        // Filter Posted Within (misalnya: last_7_days)
        $postedWithin = $request->input('posted_within');
        if ($postedWithin) {
            // Logika filter berdasarkan tanggal posting
        }

        // 4. LOGIKA SORTING (Dropdown di header)
        $sort = $request->input('sort');
        if ($sort == 'Terbaru') {
            $lowongans->latest();
        } elseif ($sort == 'Gaji Tertinggi') {
            // Logika sorting berdasarkan gaji (mungkin memerlukan parsing string gaji)
            // Contoh: $lowongans->orderBy(DB::raw('CAST(SUBSTRING_INDEX(gaji, "-", 1) AS UNSIGNED)'), 'desc');
        } else {
            $lowongans->orderBy('judul', 'asc');
        }

        // 5. Eksekusi query
        $lowongans = $lowongans->get();

        return view('lowongans.index_pelamar', compact('lowongans', 'request'));
    }
    
    // Metode show_lowongan atau detail lowongan
    // ...
}