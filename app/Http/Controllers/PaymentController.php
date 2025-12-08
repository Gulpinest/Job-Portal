<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Show subscription packages page
     */
    public function packages()
    {
        $company = Auth::user()->company;
        if (!$company) {
            return redirect()->route('company.dashboard')
                ->with('error', 'Perusahaan tidak ditemukan.');
        }

        // Check if company is verified
        if (!$company->is_verified) {
            return redirect()->route('company.dashboard')
                ->with('error', 'Perusahaan Anda belum diverifikasi oleh admin. Silakan tunggu persetujuan admin.');
        }

        $packages = Package::all();
        return view('payments.packages', compact('packages', 'company'));
    }

    /**
     * Show payment confirmation page
     */
    public function confirm(Package $package)
    {
        $company = Auth::user()->company;
        if (!$company) {
            return redirect()->route('company.dashboard')
                ->with('error', 'Perusahaan tidak ditemukan.');
        }

        // Check if company is verified
        if (!$company->is_verified) {
            return redirect()->route('company.dashboard')
                ->with('error', 'Perusahaan Anda belum diverifikasi oleh admin. Silakan tunggu persetujuan admin.');
        }

        return view('payments.confirm', compact('package', 'company'));
    }

    /**
     * Process payment request to payment gateway
     */
    public function process(Package $package)
    {
        $company = Auth::user()->company;
        if (!$company) {
            return redirect()->route('company.dashboard')
                ->with('error', 'Perusahaan tidak ditemukan.');
        }

        // Check if company is verified
        if (!$company->is_verified) {
            return redirect()->route('company.dashboard')
                ->with('error', 'Perusahaan Anda belum diverifikasi oleh admin. Silakan tunggu persetujuan admin.');
        }

        // Check if trying to buy free package
        if ($package->price == 0) {
            // Automatically activate free package
            $company->update([
                'package_id' => $package->id,
                'subscription_date' => now(),
                'subscription_expired_at' => null, // Free package has no expiry
            ]);

            return redirect()->route('payments.success-free', $company)
                ->with('success', 'Paket Gratis berhasil diaktifkan!');
        }

        $expiredHours = (int) config('services.payment.expired_hours', 24);

        // Create payment transaction record
        $transaction = PaymentTransaction::create([
            'company_id' => $company->id_company,
            'package_id' => $package->id,
            'transaction_number' => 'TRX-' . strtoupper(Str::random(12)),
            'amount' => $package->price,
            'payment_status' => 'pending',
            'expired_at' => now()->addHours($expiredHours),
        ]);

        // Create Virtual Account via Payment Gateway API
        try {
            $response = Http::withHeaders([
                'X-API-Key' => config('services.payment.api_key'),
                'Accept' => 'application/json',
            ])->withoutVerifying()->post(config('services.payment.base_url') . '/virtual-account/create', [
                'external_id' => $transaction->transaction_number,
                'amount' => $transaction->amount,
                'customer_name' => $company->nama_perusahaan,
                'customer_email' => Auth::user()->email,
                'customer_phone' => $company->no_telp_perusahaan ?? '081234567890',
                'description' => 'Pembayaran Langganan - ' . $package->nama_package,
                'expired_duration' => $expiredHours,
                'callback_url' => route('webhook.payment'),
                'metadata' => [
                    'package_id' => $package->id,
                    'company_id' => $company->id_company,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $transaction->update([
                    'va_number' => $data['data']['va_number'],
                    'payment_url' => $data['data']['payment_url'],
                ]);

                return redirect()->route('payments.waiting', $transaction);
            } else {
                $transaction->update(['payment_status' => 'failed']);
                return redirect()->route('payments.confirm', $package)
                    ->with('error', 'Gagal membuat pembayaran. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            $transaction->update(['payment_status' => 'failed']);
            return redirect()->route('payments.confirm', $package)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show waiting page for payment
     */
    public function waiting(PaymentTransaction $transaction)
    {
        $company = Auth::user()->company;
        if (!$company || $transaction->company_id !== $company->id_company) {
            abort(403);
        }

        if ($transaction->isPaid()) {
            return redirect()->route('payments.success', $transaction);
        }

        if ($transaction->isExpired()) {
            return redirect()->route('payments.packages')
                ->with('error', 'Pembayaran telah expired.');
        }

        return view('payments.waiting', compact('transaction'));
    }

    /**
     * Check payment status (AJAX)
     */
    public function checkStatus(PaymentTransaction $transaction)
    {
        try {
            $company = Auth::user()->company;
            
            if (!$company) {
                return response()->json([
                    'error' => 'Company not found',
                    'is_paid' => false,
                    'is_expired' => false,
                ], 401);
            }
            
            if ($transaction->company_id !== $company->id_company) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'is_paid' => false,
                    'is_expired' => false,
                ], 403);
            }

            return response()->json([
                'status' => $transaction->payment_status,
                'paid_at' => $transaction->paid_at?->toISOString(),
                'is_paid' => $transaction->isPaid(),
                'is_expired' => $transaction->isExpired(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'is_paid' => false,
                'is_expired' => false,
            ], 500);
        }
    }

    /**
     * Show success page after payment
     */
    public function success(PaymentTransaction $transaction)
    {
        $company = Auth::user()->company;
        if (!$company || $transaction->company_id !== $company->id_company) {
            abort(403);
        }

        if (!$transaction->isPaid()) {
            return redirect()->route('payments.waiting', $transaction);
        }

        return view('payments.success', compact('transaction'));
    }

    /**
     * Show success page for free package
     */
    public function successFree(Company $company)
    {
        if (Auth::user()->company->id_company !== $company->id_company) {
            abort(403);
        }

        return view('payments.success-free', compact('company'));
    }

    /**
     * Show payment transaction history
     */
    public function paymentHistory()
    {
        $company = Auth::user()->company;
        if (!$company) {
            return redirect()->route('company.dashboard')
                ->with('error', 'Perusahaan tidak ditemukan.');
        }

        $transactions = PaymentTransaction::where('company_id', $company->id_company)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('payments.history', compact('transactions', 'company'));
    }
}
