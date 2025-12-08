<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class LanggananController extends Controller
{
    /**
     * Menampilkan daftar paket yang tersedia
     */
    public function index()
    {
        $packages = Package::all();

        return view('langganan.index', compact('packages'));
    }

    /**
     * Proses pembelian / aktivasi paket
     */
    public function subscribe(Request $request, $packageId)
    {
        $user = auth()->user();

        if (!$user->company) {
            return back()->with('error', 'Anda belum memiliki perusahaan.');
        }

        $company = $user->company;

        // Ambil paket berdasarkan id_package (PK custom)
        $package = Package::where('id_package', $packageId)->firstOrFail();

        // Ambil durasi paket (kalau null default 30 hari)
        $duration = $package->duration_in_days ?? 30;

        $expiredAt = now()->addDays($duration);

        // Update perusahaan
        $company->update([
            'package_id'         => $package->id_package,  // sesuai PK
            'package_expired_at' => $expiredAt,
        ]);

        return redirect()
            ->route('langganan.status')
            ->with('success', 'Paket berhasil diaktifkan hingga ' . $expiredAt->format('d M Y'));
    }

    /**
     * Cek status paket aktif perusahaan
     */
    public function status()
    {
        $company = auth()->user()->company;

        if (!$company) {
            return back()->with('error', 'Anda belum memiliki perusahaan.');
        }

        return view('langganan.status', [
            'company' => $company,
            'package' => $company->package
        ]);
    }
}
