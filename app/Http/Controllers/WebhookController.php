<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    /**
     * Handle payment webhook from payment gateway
     * Supports both GET (query params) and POST (JSON body)
     */
    public function handlePayment(Request $request)
    {
        try {
            // Handle both GET (query params) and POST (JSON body)
            if ($request->isMethod('get')) {
                // Payment gateway sends GET with query parameters
                $payload = $this->parseGetPayload($request);
            } else {
                // POST with JSON body
                $payload = $request->all();
            }
            
            \Log::info('Webhook received:', $payload);
            
            // Verify webhook signature if present
            if ($request->hasHeader('X-Webhook-Signature')) {
                if (!$this->verifySignature($request)) {
                    \Log::warning('Invalid webhook signature');
                    return response()->json(['error' => 'Invalid signature'], 401);
                }
            }

            // Determine event type from payload
            $eventType = $this->getEventType($payload);

            switch ($eventType) {
                case 'payment.success':
                    return $this->handlePaymentSuccess($payload, $request);
                case 'payment.expired':
                    return $this->handlePaymentExpired($payload);
                case 'payment.cancelled':
                    return $this->handlePaymentCancelled($payload);
                default:
                    \Log::info('Unknown event type or payment success by status: ' . $eventType);
                    // If status=success in query params, treat as payment success
                    if (isset($payload['status']) && $payload['status'] === 'success') {
                        return $this->handlePaymentSuccess($payload, $request);
                    }
                    return response()->json(['error' => 'Unknown event'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Parse GET payload from query parameters
     */
    private function parseGetPayload(Request $request): array
    {
        return [
            'status' => $request->query('status'),
            'va_number' => $request->query('va_number'),
            'external_id' => $request->query('external_id'),
            'amount' => $request->query('amount'),
            'paid_at' => $request->query('paid_at'),
            'transaction_id' => $request->query('transaction_id'),
            // For compatibility with JSON payload
            'data' => [
                'va_number' => $request->query('va_number'),
                'external_id' => $request->query('external_id'),
                'amount' => $request->query('amount'),
                'paid_at' => $request->query('paid_at'),
                'transaction_id' => $request->query('transaction_id'),
            ]
        ];
    }

    /**
     * Get event type from payload (handles both JSON and query formats)
     */
    private function getEventType(array $payload): ?string
    {
        // Check for explicit event field
        if (isset($payload['event'])) {
            return $payload['event'];
        }

        // Check for status field (GET query params format)
        if (isset($payload['status'])) {
            switch ($payload['status']) {
                case 'success':
                case 'paid':
                    return 'payment.success';
                case 'expired':
                    return 'payment.expired';
                case 'cancelled':
                    return 'payment.cancelled';
            }
        }

        return null;
    }

    /**
     * Verify webhook signature using HMAC-SHA256
     */
    private function verifySignature(Request $request): bool
    {
        $signature = $request->header('X-Webhook-Signature');
        if (!$signature) {
            return false;
        }

        $webhookSecret = config('services.payment.webhook_secret');
        if (!$webhookSecret) {
            \Log::warning('Webhook secret not configured');
            return false;
        }

        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

        return hash_equals($signature, $expectedSignature);
    }

    /**
     * Handle payment success
     */
    private function handlePaymentSuccess(array $payload, Request $request): RedirectResponse|JsonResponse
    {
        $data = $payload['data'] ?? [];
        
        // Support both nested 'data' format and flat format
        $externalId = $data['external_id'] ?? $payload['external_id'] ?? null;
        $vaNumber = $data['va_number'] ?? $payload['va_number'] ?? null;
        
        // Try to find transaction by external_id first, then by va_number
        $transaction = null;
        
        if ($externalId) {
            $transaction = PaymentTransaction::where('transaction_number', $externalId)->first();
            if (!$transaction) {
                \Log::warning('Transaction not found by external_id: ' . $externalId);
            }
        }
        
        // If not found by external_id, try by va_number
        if (!$transaction && $vaNumber) {
            $transaction = PaymentTransaction::where('va_number', $vaNumber)->first();
            if (!$transaction) {
                \Log::warning('Transaction not found by va_number: ' . $vaNumber);
            }
        }
        
        if (!$transaction) {
            if ($request->isMethod('get')) {
                return redirect()->route('payments.success')->with('error', 'Transaction not found');
            }
            return response()->json(['error' => 'Transaction not found (searched by external_id and va_number)'], 404);
        }

        // Check if already paid (idempotency)
        if ($transaction->isPaid()) {
            \Log::info('Transaction already paid: ' . $transaction->transaction_number);
            if ($request->isMethod('get')) {
                return redirect()->route('payments.success', $transaction);
            }
            return response()->json(['message' => 'Already processed'], 200);
        }

        try {
            // Update transaction as paid
            $transaction->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            // Get the package and company
            $package = $transaction->package;
            $company = $transaction->company;

            if (!$package || !$company) {
                throw new \Exception('Package or Company not found');
            }

            // Calculate new subscription expiry
            $durationMonths = $package->duration_months ?? 0;

            if ($company->subscription_expired_at && $company->subscription_expired_at->isFuture()) {
                // If subscription is still active, add months to current expiry
                $newExpiryDate = $company->subscription_expired_at->addMonths($durationMonths);
            } else {
                // If subscription is expired or null, start fresh
                $newExpiryDate = now()->addMonths($durationMonths);
            }

            // Update company subscription
            $company->update([
                'package_id' => $package->id,
                'subscription_date' => $company->subscription_date ?? now(),
                'subscription_expired_at' => $durationMonths > 0 ? $newExpiryDate : null,
            ]);

            \Log::info('Payment confirmed for transaction: ' . $transaction->transaction_number);

            // TODO: Send email notification to company

            // Redirect to success page instead of returning JSON
            // This provides better UX when payment gateway redirects user to webhook endpoint
            if ($request->isMethod('get')) {
                // For GET requests, redirect to success page
                return redirect()->route('payments.success', $transaction);
            } else {
                // For POST requests (async webhooks), return JSON
                return response()->json([
                    'message' => 'Payment processed successfully',
                    'transaction_id' => $transaction->id,
                    'company_id' => $company->id_company,
                ], 200);
            }
        } catch (\Exception $e) {
            \Log::error('Error processing payment: ' . $e->getMessage());
            if ($request->isMethod('get')) {
                return redirect()->route('payments.success')->with('error', $e->getMessage());
            }
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle payment cancelled
     */
    private function handlePaymentCancelled(array $payload): \Illuminate\Http\JsonResponse
    {
        $data = $payload['data'] ?? [];
        $externalId = $data['external_id'] ?? $payload['external_id'] ?? null;
        $vaNumber = $data['va_number'] ?? $payload['va_number'] ?? null;

        $transaction = null;
        if ($externalId) {
            $transaction = PaymentTransaction::where('transaction_number', $externalId)->first();
        } elseif ($vaNumber) {
            $transaction = PaymentTransaction::where('va_number', $vaNumber)->first();
        }

        if ($transaction) {
            $transaction->update(['payment_status' => 'cancelled']);
            \Log::info('Payment cancelled for transaction: ' . $transaction->transaction_number);
        } else {
            \Log::warning('Transaction not found for cancellation (external_id: ' . $externalId . ', va_number: ' . $vaNumber . ')');
        }

        return response()->json(['message' => 'Payment cancelled recorded'], 200);
    }

    /**
     * Handle payment expired
     */
    private function handlePaymentExpired(array $payload): \Illuminate\Http\JsonResponse
    {
        $data = $payload['data'] ?? [];
        $externalId = $data['external_id'] ?? $payload['external_id'] ?? null;
        $vaNumber = $data['va_number'] ?? $payload['va_number'] ?? null;

        $transaction = null;
        if ($externalId) {
            $transaction = PaymentTransaction::where('transaction_number', $externalId)->first();
        } elseif ($vaNumber) {
            $transaction = PaymentTransaction::where('va_number', $vaNumber)->first();
        }

        if ($transaction) {
            $transaction->update(['payment_status' => 'expired']);
            \Log::info('Payment expired for transaction: ' . $transaction->transaction_number);
        } else {
            \Log::warning('Transaction not found for expiration (external_id: ' . $externalId . ', va_number: ' . $vaNumber . ')');
        }

        return response()->json(['message' => 'Payment expired recorded'], 200);
    }
}
