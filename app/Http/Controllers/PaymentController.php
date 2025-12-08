<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Company;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        // 1. Cek apakah sudah verifikasi
        if ($company->is_verified) {
            return redirect()->back()->with('error', 'Akun Anda sudah terverifikasi!');
        }

        // 2. Siapkan Data
        $orderId = 'INV-' . time() . '-' . $company->id_company; // ID Unik
        $amount = 150000; // Harga Verifikasi

        // 3. Kirim ke API Doovera [cite: 22]
        $response = Http::withHeaders([
            'X-API-Key' => env('PAYMENT_API_KEY') // Ambil dari .env [cite: 14]
        ])->post(env('PAYMENT_BASE_URL') . '/virtual-account/create', [
            'external_id' => $orderId,
            'amount' => $amount,
            'customer_name' => $company->nama_perusahaan,
            'customer_email' => $user->email,
            'description' => 'Verifikasi Akun ' . $company->nama_perusahaan,
            'expired_duration' => 24, // 24 Jam [cite: 30]
        ]);

        // 4. Cek Respon
        if ($response->successful()) {
            $data = $response->json()['data']; // [cite: 40]

            // Simpan ke Database
            Payment::create([
                'id_company' => $company->id_company,
                'order_id' => $orderId,
                'external_id' => $data['external_id'],
                'amount' => $data['amount'],
                'status' => 'pending',
                'va_number' => $data['va_number'],     // [cite: 41]
                'checkout_link' => $data['payment_url'] // [cite: 45]
            ]);

            // Arahkan user ke halaman pembayaran Doovera
            return redirect($data['payment_url']);
        } else {
            return redirect()->back()->with('error', 'Gagal membuat pembayaran: ' . $response->body());
        }
    }

    // Webhook: Menerima notifikasi otomatis dari Doovera [cite: 123]
    public function webhook(Request $request)
    {
        // 1. Verifikasi Signature (Keamanan) [cite: 145]
        $signature = $request->header('X-Webhook-Signature');
        $payload = $request->getContent();
        $secret = env('PAYMENT_WEBHOOK_SECRET'); // Ambil dari .env [cite: 149]

        $expectedSignature = hash_hmac('sha256', $payload, $secret); // [cite: 150]

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json(['message' => 'Invalid Signature'], 401);
        }

        // 2. Proses Data
        $data = $request->input('data');
        $externalId = $data['external_id'];
        $event = $request->input('event'); // [cite: 134]

        $payment = Payment::where('order_id', $externalId)->first();

        if ($payment) {
            if ($event === 'payment.success') { // [cite: 126]
                // Update Status Pembayaran
                $payment->update(['status' => 'paid']);
                
                // Update Status Perusahaan jadi Terverifikasi
                $company = Company::find($payment->id_company);
                if ($company) {
                    $company->update([
                        'is_verified' => true,
                        'verified_at' => now()
                    ]);
                }
            } elseif ($event === 'payment.expired') { // [cite: 128]
                $payment->update(['status' => 'expired']);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}