<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    // 1. Tampilkan Halaman Harga
    public function index()
    {
        return view('subscription.index');
    }

    // 2. Proses Pembelian (Simulasi)
    public function buy(Request $request)
    {
        $package = $request->packet; // bronze, silver, gold
        $quotaToAdd = 0;
        $price = 0;

        // Tentukan jumlah kuota berdasarkan paket
        switch ($package) {
            case 'bronze':
                $quotaToAdd = 6;
                $price = 300000;
                break;
            case 'silver':
                $quotaToAdd = 13;
                $price = 450000;
                break;
            case 'gold':
                $quotaToAdd = 25;
                $price = 600000;
                break;
            default:
                return back()->with('error', 'Paket tidak valid');
        }

        // --- DI SINI INTEGRASI PAYMENT GATEWAY (MIDTRANS/XENDIT) ---
        // Untuk sekarang, kita buat "Auto Success" (Simulasi) agar logika jalan dulu.

        $user = Auth::user();
        $company = $user->company;

        // Tambahkan kuota ke database
        $company->job_quota += $quotaToAdd;
        $company->save();

        return redirect()->route('lowongans.create')->with('success', 'Pembelian berhasil! Kuota bertambah ' . $quotaToAdd);
    }
}
