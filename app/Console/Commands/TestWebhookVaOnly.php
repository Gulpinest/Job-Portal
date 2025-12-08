<?php

namespace App\Console\Commands;

use App\Models\PaymentTransaction;
use Illuminate\Console\Command;

class TestWebhookVaOnly extends Command
{
    protected $signature = 'webhook:test-va-only';
    protected $description = 'Test webhook with only va_number (no external_id)';

    public function handle()
    {
        $this->info("Testing webhook with only va_number (payment gateway format)\n");

        // Create test transaction
        $this->info("Step 1: Creating test transaction...");
        $company = \App\Models\Company::first();
        $package = \App\Models\Package::where('nama_package', 'Premium - 6 Bulan')->first() ?? \App\Models\Package::first();

        if (!$company || !$package) {
            $this->error('Company or Package not found');
            return;
        }

        $vaNumber = '8800002222332226';
        $transaction = PaymentTransaction::create([
            'company_id' => $company->id_company,
            'package_id' => $package->id,
            'transaction_number' => 'TRX-' . \Illuminate\Support\Str::random(12),
            'amount' => $package->price,
            'payment_status' => 'pending',
            'va_number' => $vaNumber,
            'payment_url' => 'https://payment.example.com',
            'expired_at' => now()->addHours(24),
        ]);

        $this->info("✓ Transaction created");
        $this->info("  - Transaction ID: {$transaction->id}");
        $this->info("  - Transaction Number: {$transaction->transaction_number}");
        $this->info("  - VA Number: {$transaction->va_number}");
        $this->info("  - Status: {$transaction->payment_status}\n");

        // Test webhook with ONLY va_number (no external_id)
        $this->info("Step 2: Sending webhook with ONLY va_number...");
        
        $params = [
            'status' => 'success',
            'va_number' => $vaNumber,
            // NOTE: NO external_id!
        ];

        $url = url('/webhook/payment') . '?' . http_build_query($params);
        
        $this->info("URL: {$url}");
        $this->info("Query params: " . json_encode($params) . "\n");

        try {
            $response = \Illuminate\Support\Facades\Http::get($url);

            $this->info("Response status: {$response->status()}");
            $this->info("Response body: {$response->body()}\n");

            if ($response->ok()) {
                $this->info("✓ Webhook processed successfully!");
                
                // Check transaction status after webhook
                $transaction->refresh();
                $this->info("\nStep 3: Verify transaction updated...");
                $this->info("  - Status: {$transaction->payment_status}");
                $this->info("  - isPaid(): " . ($transaction->isPaid() ? 'YES ✓' : 'NO ✗'));
                $this->info("  - Paid at: {$transaction->paid_at}");

                // Verify company subscription
                $company->refresh();
                $this->info("\nStep 4: Verify company subscription...");
                $this->info("  - Package: {$company->package?->nama_package}");
                $this->info("  - Subscription expired: {$company->subscription_expired_at}");
                $this->info("  - Is verified: " . ($company->is_verified ? 'YES' : 'NO'));

                if ($transaction->isPaid() && $company->package_id === $package->id) {
                    $this->info("\n✓✓✓ SUCCESS! Webhook processed correctly with only va_number!");
                } else {
                    $this->warn("\n⚠ Warning: Webhook processed but data might not be updated correctly");
                }
            } else {
                $this->error("✗ Webhook failed!");
            }
        } catch (\Exception $e) {
            $this->error("✗ Error: " . $e->getMessage());
        }
    }
}
