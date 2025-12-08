<?php

namespace App\Console\Commands;

use App\Models\PaymentTransaction;
use Illuminate\Console\Command;

class TestFullPaymentFlow extends Command
{
    protected $signature = 'payment:test-flow';
    protected $description = 'Test full payment flow: create transaction -> check status -> webhook -> check status again';

    public function handle()
    {
        $this->info("========== FULL PAYMENT FLOW TEST ==========\n");

        // Step 1: Create transaction
        $this->info("Step 1: Creating test transaction...");
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

        $this->info("✓ Transaction created: {$transaction->transaction_number}");
        $this->info("  - ID: {$transaction->id}");
        $this->info("  - Status: {$transaction->payment_status}");
        $this->info("  - Amount: " . number_format($transaction->amount, 0) . "\n");

        // Step 2: Check status BEFORE webhook
        $this->info("Step 2: Check status BEFORE webhook (should be pending)...");
        $this->checkPaymentStatus($transaction);

        // Step 3: Simulate webhook payment success
        $this->info("\nStep 3: Simulating webhook payment success...");
        $this->simulateWebhook($transaction);

        // Step 4: Check status AFTER webhook
        $this->info("\nStep 4: Check status AFTER webhook (should be paid)...");
        $transaction->refresh();
        $this->checkPaymentStatus($transaction);

        // Step 5: Verify company subscription updated
        $this->info("\nStep 5: Verify company subscription updated...");
        $company->refresh();
        $this->info("Company package: " . $company->package?->nama_package);
        $this->info("Subscription expired: " . $company->subscription_expired_at);
        $this->info("Is verified: " . ($company->is_verified ? 'Yes' : 'No'));

        $this->info("\n========== TEST COMPLETE ==========");
    }

    private function checkPaymentStatus(PaymentTransaction $transaction)
    {
        $this->info("  - Transaction ID: {$transaction->id}");
        $this->info("  - Current status: {$transaction->payment_status}");
        $this->info("  - isPaid(): " . ($transaction->isPaid() ? 'YES ✓' : 'NO ✗'));
        $this->info("  - isExpired(): " . ($transaction->isExpired() ? 'YES' : 'NO ✓'));
    }

    private function simulateWebhook(PaymentTransaction $transaction)
    {
        $params = [
            'status' => 'success',
            'va_number' => $transaction->va_number,
            'external_id' => $transaction->transaction_number,
            'amount' => (int) $transaction->amount,
            'paid_at' => now()->toIso8601String(),
            'transaction_id' => 'TRX-' . \Illuminate\Support\Str::random(12),
        ];

        $url = url('/webhook/payment') . '?' . http_build_query($params);
        
        $this->info("  - Sending webhook: GET {$url}");

        try {
            $response = \Illuminate\Support\Facades\Http::get($url);

            $this->info("  - Response status: {$response->status()}");
            if ($response->ok()) {
                $this->info("  - ✓ Webhook successful");
            } else {
                $this->error("  - ✗ Webhook failed: " . $response->body());
            }
        } catch (\Exception $e) {
            $this->error("  - ✗ Webhook error: " . $e->getMessage());
        }
    }
}
