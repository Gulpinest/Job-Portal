<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    /**
     * Menampilkan daftar semua resume milik pelamar yang sedang login.
     */
    public function index()
    {
        // Mendapatkan data pelamar yang terautentikasi.
        // Asumsi: Setiap User memiliki satu relasi 'pelamar' dan objek 'pelamar' memiliki 'id_pelamar'.
        $pelamar = Auth::user()->pelamar;

        // Ambil semua resume yang 'id_pelamar'-nya cocok, urutkan dari yang terbaru.
        $resumes = Resume::where('id_pelamar', $pelamar->id_pelamar)
                         ->latest()
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
            'skill' => 'required|string|max:500', // Diperluas max:255 ke max:500
            'pendidikan_terakhir' => 'required|string|max:50', // Diperbaiki: Wajib diisi (Sesuai form create)
            'ringkasan_singkat' => 'nullable|string|max:300', // Diperbaiki: Max 300 karakter
            'file_resume' => 'required|file|mimes:pdf,doc,docx|max:2048', // Maks 2MB
        ]);

        $pelamar = Auth::user()->pelamar;

        // 2. Upload file ke storage (folder: storage/app/public/resumes).
        $filePath = $request->file('file_resume')->store('resumes', 'public');

        // 3. Simpan data ke database.
        Resume::create([
            'id_pelamar' => $pelamar->id_pelamar,
            'nama_resume' => $request->nama_resume,
            'skill' => $request->skill,
            // --- FIELD BARU DITAMBAHKAN ---
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'ringkasan_singkat' => $request->ringkasan_singkat,
            // -----------------------------
            'file_resume' => $filePath,
        ]);

        return redirect()->route('resumes.index')
                         ->with('success', 'Resume baru berhasil ditambahkan!');
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

        // 1. Validasi data. 'file_resume' tidak 'required'.
        $validatedData = $request->validate([
            'nama_resume' => 'required|string|max:255',
            'skill' => 'required|string|max:500',
            'pendidikan_terakhir' => 'required|string|max:50',
            'ringkasan_singkat' => 'nullable|string|max:300',
            'file_resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);
        
        $dataToUpdate = $validatedData;
        unset($dataToUpdate['file_resume']); // Hapus dari array update jika tidak ada file baru

        // 2. Cek apakah ada file baru yang diupload.
        if ($request->hasFile('file_resume')) {
            // Hapus file lama dari storage.
            Storage::disk('public')->delete($resume->file_resume);
            // Upload file baru dan simpan path-nya.
            $dataToUpdate['file_resume'] = $request->file('file_resume')->store('resumes', 'public');
        }

        // 3. Update data di database.
        $resume->update($dataToUpdate);

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
        if ($resume->file_resume) {
            Storage::disk('public')->delete($resume->file_resume);
        }

        // 2. Hapus data dari database.
        $resume->delete();

        return redirect()->route('resumes.index')
                         ->with('success', 'Resume berhasil dihapus!');
    }
    
    // (Metode show dihilangkan karena jarang digunakan untuk list file)
}