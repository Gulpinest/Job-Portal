<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Company;
use App\Models\Package;

class PaymentController extends Controller
{
    public function subscribe(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        
        // 1. Validasi Input (ID Paket 2 atau 3)
        $request->validate([
            'package_id' => 'required|exists:packages,id'
        ]);

        $package = Package::findOrFail($request->package_id);

        // Cek jika user mencoba beli paket gratis (ID 1) atau paket yang sama
        if ($package->price <= 0) {
            return redirect()->back()->with('error', 'Paket ini gratis, otomatis aktif.');
        }

        // 2. Siapkan Data Payment
        $amount = $package->price;
        $orderId = 'SUB-' . $package->id . '-' . time() . '-' . $company->id_company;

        // 3. Kirim ke Doovera
        $response = Http::withHeaders([
            'X-API-Key' => env('PAYMENT_API_KEY')
        ])->post(env('PAYMENT_BASE_URL') . '/virtual-account/create', [
            'external_id' => $orderId,
            'amount' => $amount,
            'customer_name' => $company->nama_perusahaan,
            'customer_email' => $user->email,
            'description' => 'Langganan ' . $package->name,
            'expired_duration' => 24,
        ]);

        // 4. Proses Respon
        if ($response->successful()) {
            $data = $response->json()['data'];
            
            Payment::create([
                'id_company' => $company->id_company,
                'package_id' => $package->id, // Simpan ID paket yang dibeli
                'order_id' => $orderId,
                'external_id' => $data['external_id'],
                'amount' => $data['amount'],
                'status' => 'pending',
                'checkout_link' => $data['payment_url']
            ]);

            return redirect($data['payment_url']);
        } else {
            return redirect()->back()->with('error', 'Gagal: ' . $response->body());
        }
    }

    public function webhook(Request $request)
    {
        // 1. Verifikasi Signature
        $signature = $request->header('X-Webhook-Signature');
        $payload = $request->getContent();
        $secret = env('PAYMENT_WEBHOOK_SECRET');

        if (!hash_equals(hash_hmac('sha256', $payload, $secret), $signature)) {
            return response()->json(['message' => 'Invalid Signature'], 401);
        }

        // 2. Proses Data
        $data = $request->input('data');
        $externalId = $data['external_id'];
        $event = $request->input('event');

        if ($event === 'payment.success') {
            $payment = Payment::where('order_id', $externalId)->first();
            
            if ($payment) {
                $payment->update(['status' => 'paid']);

                // Update Paket Perusahaan
                $company = Company::find($payment->id_company);
                if ($company) {
                    $company->update([
                        'package_id' => $payment->package_id, // Update ke ID paket baru
                    ]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}