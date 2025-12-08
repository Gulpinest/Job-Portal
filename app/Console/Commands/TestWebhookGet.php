<?php

namespace App\Console\Commands;

use App\Models\PaymentTransaction;
use Illuminate\Console\Command;

class TestWebhookGet extends Command
{
    protected $signature = 'webhook:test-get {transaction_number? : Transaction number to test}';
    protected $description = 'Test webhook with GET query parameters (like payment gateway)';

    public function handle()
    {
        $transactionNumber = $this->argument('transaction_number');

        // Jika tidak ada transaction_number, buat transaction test
        if (!$transactionNumber) {
            $this->info('Creating test transaction...');
            
            $company = \App\Models\Company::first();
            $package = \App\Models\Package::where('nama_package', 'Premium - 6 Bulan')->first() ?? \App\Models\Package::first();

            if (!$company || !$package) {
                $this->error('Company or Package not found');
                return;
            }

            $transaction = PaymentTransaction::create([
                'company_id' => $company->id_company,
                'package_id' => $package->id,
                'transaction_number' => 'TRX-' . \Illuminate\Support\Str::random(12),
                'amount' => $package->price,
                'payment_status' => 'pending',
                'va_number' => '8800002039188084',
                'payment_url' => 'https://payment.example.com',
                'expired_at' => now()->addHours(24),
            ]);

            $transactionNumber = $transaction->transaction_number;
            $this->info("Transaction created: {$transactionNumber}");
        }

        // Find transaction
        $transaction = PaymentTransaction::where('transaction_number', $transactionNumber)->first();

        if (!$transaction) {
            $this->error("Transaction not found: {$transactionNumber}");
            return;
        }

        $this->info("Testing webhook GET request for transaction: {$transactionNumber}");
        $this->info("Current status: {$transaction->payment_status}");

        // Build GET URL with query params
        $params = [
            'status' => 'success',
            'va_number' => $transaction->va_number ?? '8800002039188084',
            'external_id' => $transaction->transaction_number,
            'amount' => (int) $transaction->amount,
            'paid_at' => now()->toIso8601String(),
            'transaction_id' => 'TRX-' . \Illuminate\Support\Str::random(12),
        ];

        $url = url('/webhook/payment') . '?' . http_build_query($params);
        
        $this->info("URL: {$url}");

        try {
            $response = \Illuminate\Support\Facades\Http::get($url);

            $this->info("Response status: {$response->status()}");
            $this->info("Response: " . $response->body());

            // Check transaction status after webhook
            $transaction->refresh();
            $this->info("Transaction status after webhook: {$transaction->payment_status}");

            if ($transaction->isPaid()) {
                $this->info("✓ Payment status updated successfully!");
                $this->info("Company subscription updated:");
                $transaction->company->refresh();
                $this->info("  - Package: {$transaction->company->package?->nama_package}");
                $this->info("  - Subscription expired: {$transaction->company->subscription_expired_at}");
            }

            $this->info("\n✓ Webhook GET test completed");
        } catch (\Exception $e) {
            $this->error("Webhook test failed: " . $e->getMessage());
        }
    }
}
