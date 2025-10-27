<?php

namespace App\Http\Controllers;

use App\Models\Resume; // Pastikan Model Resume di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mengambil data user yang login
use Illuminate\Support\Facades\Storage; // Untuk mengelola file (upload, delete)

class ResumeController extends Controller
{
    /**
     * Menampilkan daftar semua resume milik pelamar yang sedang login.
     */
    public function index()
    {
        // Asumsi: Setiap User memiliki satu relasi 'pelamar'.
        $pelamar = Auth::user()->pelamar;

        // return dd(Auth::user()->isPelamar());

        // Ambil semua resume yang 'id_pelamar'-nya cocok, urutkan dari yang terbaru.
        $resumes = Resume::where('id_pelamar', $pelamar->id_pelamar)
                         ->latest() // Mengurutkan berdasarkan created_at (terbaru dulu)
                         ->get();

        return view('resumes.index', compact('resumes'));
    }

    /**
     * Menampilkan form untuk membuat resume baru.
     */
    public function create()
    {
        return view('resumes.create');
    }

    /**
     * Menyimpan data resume baru ke dalam database.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari form.
        $request->validate([
            'nama_resume' => 'required|string|max:255',
            'file_resume' => 'required|file|mimes:pdf,doc,docx|max:2048', // File harus pdf/doc/docx, maks 2MB
        ]);

        $pelamar = Auth::user()->pelamar;

        // 2. Upload file ke storage (folder: storage/app/public/resumes).
        $filePath = $request->file('file_resume')->store('resumes', 'public');

        // 3. Simpan data ke database.
        Resume::create([
            'id_pelamar' => $pelamar->id_pelamar,
            'nama_resume' => $request->nama_resume,
            'file_resume' => $filePath,
        ]);

        return redirect()->route('resumes.index')
                         ->with('success', 'Resume baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu resume. (Opsional, bisa jadi tidak terpakai).
     */
    public function show(Resume $resume)
    {
        // Fitur ini biasanya untuk halaman detail, mungkin bisa dilewati.
        // Untuk keamanan, pastikan resume ini milik pelamar yang sedang login.
        if ($resume->id_pelamar !== Auth::user()->pelamar->id_pelamar) {
            abort(403); // Akses ditolak
        }

        return view('resumes.show', compact('resume'));
    }

    /**
     * Menampilkan form untuk mengedit data resume.
     */
    public function edit(Resume $resume)
    {
        // Keamanan: Pastikan yang mau mengedit adalah pemilik resume.
        if ($resume->id_pelamar !== Auth::user()->pelamar->id_pelamar) {
            abort(403, 'ANDA TIDAK PUNYA AKSES UNTUK MENGEDIT RESUME INI');
        }

        return view('resumes.edit', compact('resume'));
    }

    /**
     * Memperbarui data resume di dalam database.
     */
    public function update(Request $request, Resume $resume)
    {
        // Keamanan: Pastikan yang mau update adalah pemilik resume.
        if ($resume->id_pelamar !== Auth::user()->pelamar->id_pelamar) {
            abort(403);
        }

        // 1. Validasi data. 'file_resume' tidak 'required' karena bisa saja user hanya ganti nama.
        $request->validate([
            'nama_resume' => 'required|string|max:255',
            'file_resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath = $resume->file_resume; // Simpan path file lama sebagai default.

        // 2. Cek apakah ada file baru yang diupload.
        if ($request->hasFile('file_resume')) {
            // Hapus file lama dari storage.
            Storage::disk('public')->delete($resume->file_resume);
            // Upload file baru dan simpan path-nya.
            $filePath = $request->file('file_resume')->store('resumes', 'public');
        }

        // 3. Update data di database.
        $resume->update([
            'nama_resume' => $request->nama_resume,
            'file_resume' => $filePath,
        ]);

        return redirect()->route('resumes.index')
                         ->with('success', 'Resume berhasil diperbarui!');
    }

    /**
     * Menghapus data resume dari database.
     */
    public function destroy(Resume $resume)
    {
        // Keamanan: Pastikan yang mau menghapus adalah pemilik resume.
        if ($resume->id_pelamar !== Auth::user()->pelamar->id_pelamar) {
            abort(403);
        }

        // 1. Hapus file dari storage terlebih dahulu.
        Storage::disk('public')->delete($resume->file_resume);

        // 2. Hapus data dari database.
        $resume->delete();

        return redirect()->route('resumes.index')
                         ->with('success', 'Resume berhasil dihapus!');
    }
}
