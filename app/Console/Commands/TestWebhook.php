<?php

namespace App\Console\Commands;

use App\Models\PaymentTransaction;
use App\Models\Company;
use App\Models\Package;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:test {transaction_number? : Transaction number to test} {--event=payment.success : Event type (payment.success, payment.cancelled, payment.expired)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test webhook payment processing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactionNumber = $this->argument('transaction_number');
        $eventType = $this->option('event');

        // Jika tidak ada transaction_number, buat transaction test
        if (!$transactionNumber) {
            $this->info('Membuat transaction test...');
            
            $company = Company::first();
            $package = Package::where('nama_package', 'Premium - 6 Bulan')->first() ?? Package::first();

            if (!$company || !$package) {
                $this->error('Company atau Package tidak ditemukan');
                return;
            }

            $transaction = PaymentTransaction::create([
                'company_id' => $company->id_company,
                'package_id' => $package->id,
                'transaction_number' => 'TRX-' . strtoupper(Str::random(12)),
                'amount' => $package->price,
                'payment_status' => 'pending',
                'va_number' => '123456789',
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

        $this->info("Testing webhook for transaction: {$transactionNumber}");
        $this->info("Current status: {$transaction->payment_status}");
        $this->info("Event type: {$eventType}");

        // Prepare payload sesuai dokumentasi API
        $payload = [
            'event' => $eventType,
            'timestamp' => now()->toIso8601String(),
            'data' => [
                'va_number' => $transaction->va_number ?? '8800008941019061',
                'external_id' => $transaction->transaction_number,
                'amount' => (int) $transaction->amount,
                'paid_at' => now()->toIso8601String(),
                'payment_method' => 'bank_transfer',
                'transaction_id' => 'TRX-' . strtoupper(\Illuminate\Support\Str::random(12)),
            ]
        ];

        // Calculate signature
        $secret = config('services.payment.webhook_secret');
        $payloadJson = json_encode($payload);
        $signature = hash_hmac('sha256', $payloadJson, $secret);

        $this->info("Payload: " . json_encode($payload, JSON_PRETTY_PRINT));
        $this->info("Signature: {$signature}");
        // Simulate webhook call using HTTP client
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'X-Webhook-Signature' => $signature,
            ])->post(url('/webhook/payment'), $payload);

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

            $this->info("\n✓ Webhook test completed");
        } catch (\Exception $e) {
            $this->error("Webhook test failed: " . $e->getMessage());
        }
    }
}
