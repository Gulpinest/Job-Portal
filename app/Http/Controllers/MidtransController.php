<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
use App\Models\Transaction; // Import Model Transaction

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // Fungsi untuk membuat Transaksi SNAP
    public function createTransaction(Request $request)
    {
        // 1. Validasi Input (Hanya perlu ID paket)
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            // Hapus 'billing_cycle' dari validasi
        ]);

        $package = \App\Models\Package::findOrFail($request->package_id); // Pastikan import App\Models\Package
        $user = Auth::user();

        // 2. Tentukan Harga (Ambil dari kolom 'price' tunggal)
        $grossAmount = $package->price; // <-- Hanya menggunakan kolom 'price'

        // ID Transaksi
        $midtransOrderId = 'INV-' . time() . '-' . $user->id;

        // 3. Buat Entri Transaksi di Database Anda
        $transaction = \App\Models\Transaction::create([ // Pastikan import App\Models\Transaction
            'user_id' => $user->id,
            'package_id' => $package->id,
            'midtrans_order_id' => $midtransOrderId,
            'gross_amount' => $grossAmount,
            // Hapus 'billing_cycle' dari sini
            'payment_status' => 'pending', 
        ]);

        // 4. Siapkan Parameter Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '08xxxxxxxxxx', 
            ],
            'item_details' => [
                [
                    'id' => $package->id,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => 'Langganan Paket ' . $package->name // Nama Paket
                ]
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->snap_token = $snapToken;
            $transaction->save();

            return response()->json([
                'token' => $snapToken,
                'order_id' => $midtransOrderId
            ]);
        } catch (\Exception $e) {
            $transaction->delete(); 
            return response()->json(['error' => 'Gagal mendapatkan Midtrans Snap Token: ' . $e->getMessage()], 500);
        }
    }

    // Fungsi untuk menerima Notifikasi/Webhook dari Midtrans
    public function handleNotification(Request $request)
    {
        // 1. Verifikasi Notifikasi (Midtrans SDK otomatis memverifikasi signature)
        $notification = new \Midtrans\Notification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;

        // 2. Cari Transaksi di Database Anda
        $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

        if (!$transaction) {
            return response('Order ID Not Found', 404);
        }

        // 3. Logika Pembaruan Status
        if ($transaction->payment_status != 'settlement') { // Hanya proses jika belum lunas
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'accept' || $transactionStatus == 'settlement') {
                    // BERHASIL: Set status transaksi di DB menjadi 'settlement'
                    $transaction->payment_status = 'settlement';
                    $transaction->save();

                    $company = $transaction->user->company; // Asumsi User punya relasi 'company'
                    $package = $transaction->package;
                
                // Tentukan periode (misalnya, 12 bulan karena harganya besar)
                    $periodInMonths = 0;
                    if ($package->id === '0') {
                        $periodInMonths = 1;
                    } elseif ($package->name === 'Silver') {
                        $periodInMonths = 6; 
                    } elseif ($package->name === 'Gold') {
                        $periodInMonths = 12;
                    } else {
                        $periodInMonths = 1; 
                    }
                    
                    // TODO: LOGIKA PENTING! Aktifkan paket/subscription untuk user/company terkait
                    if ($company && $periodInMonths > 0) {
    
                        // 1. Tambahkan kuota (Asumsi ada kolom job_limit di Model Package)
                        $company->job_quota += $package->job_limit; 
                        
                        // 2. Set ID dan Tanggal Berakhir di Model Company
                        $company->package_id = $transaction->package_id;
                        
                        // Gunakan Carbon untuk menghitung tanggal berakhir
                        $company->subscription_ends_at = now()->addMonths($periodInMonths); 
                        
                        // 3. Simpan perubahan ke tabel 'companies'
                        $company->save();}

                }
            } else if ($transactionStatus == 'pending') {
                $transaction->payment_status = 'pending';
                $transaction->save();
            } else if ($transactionStatus == 'expire' || $transactionStatus == 'cancel' || $transactionStatus == 'deny') {
                $transaction->payment_status = 'cancelled';
                $transaction->save();
            }
        }
        
        // Midtrans mengharapkan response 200 OK
        return response('OK', 200);
    }
}