# ✅ Webhook Integration - Final Update

## Problem & Solution

**Problem:** Payment gateway mengirim callback dengan GET request, tapi route hanya support POST.

```
Error: The GET method is not supported for route webhook/payment
URL: GET http://yourapp.com/webhook/payment?status=success&va_number=8800002039188084
```

**Solution:** Update webhook untuk support both GET dan POST request.

## Implementation

### 1. Route Configuration
```php
// routes/web.php
Route::match(['get', 'post'], '/webhook/payment', [WebhookController::class, 'handlePayment'])->name('webhook.payment');
```

### 2. Webhook Controller Updates

#### Supports 2 Payload Formats:

**Format 1: POST dengan JSON body (dari webhook JSON)**
```json
{
  "event": "payment.success",
  "timestamp": "2025-12-08T15:44:39+00:00",
  "data": {
    "va_number": "8800002039188084",
    "external_id": "TRX-XXXX",
    "amount": 4500000,
    "paid_at": "2025-12-08T15:44:39+00:00"
  }
}
```

**Format 2: GET dengan query parameters (dari payment gateway callback)**
```
/webhook/payment?status=success&va_number=8800002039188084&external_id=TRX-XXXX&amount=4500000
```

#### Key Methods:

- `handlePayment()` - Detect request method dan route ke handler yang tepat
- `parseGetPayload()` - Convert GET query params ke format internal
- `getEventType()` - Ekstrak event type dari berbagai format
- `handlePaymentSuccess()` - Support both nested 'data' dan flat format
- `verifySignature()` - Optional (hanya jika X-Webhook-Signature header ada)

### 3. Features

✅ **Dual Format Support**
- POST JSON body
- GET query parameters

✅ **Flexible External ID Extraction**
- Dari nested data: `$payload['data']['external_id']`
- Dari flat format: `$payload['external_id']`

✅ **Event Type Detection**
- Explicit event field: `'event' => 'payment.success'`
- Status field: `'status' => 'success'` → converted to `'payment.success'`

✅ **Signature Verification**
- Optional (hanya jika header ada)
- Tidak required untuk GET request

✅ **Idempotency**
- Prevent duplicate processing
- Check isPaid() sebelum update

## Testing

### Test dengan POST JSON
```bash
php artisan webhook:test --event=payment.success
```

### Test dengan GET Query Params
```bash
php artisan webhook:test-get
```

### Manual Test dengan cURL

**POST Format:**
```bash
curl -X POST http://localhost:8000/webhook/payment \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Signature: signature-hash" \
  -d '{
    "event": "payment.success",
    "data": {
      "external_id": "TRX-XXXX",
      "amount": 4500000
    }
  }'
```

**GET Format:**
```bash
curl -X GET "http://localhost:8000/webhook/payment?status=success&external_id=TRX-XXXX&amount=4500000"
```

## Event Types Supported

| Event | GET Status | JSON Event | Handler |
|-------|-----------|-----------|---------|
| Payment Success | `status=success` | `event=payment.success` | handlePaymentSuccess() |
| Payment Expired | `status=expired` | `event=payment.expired` | handlePaymentExpired() |
| Payment Cancelled | `status=cancelled` | `event=payment.cancelled` | handlePaymentCancelled() |

## Error Handling

| Status | Error | Action |
|--------|-------|--------|
| 200 | None | Payment processed successfully |
| 401 | Invalid signature | Signature verification failed |
| 404 | Transaction not found | External ID tidak ditemukan di database |
| 400 | Missing external_id | External ID tidak ada di payload |
| 400 | Unknown event | Event type tidak dikenali |
| 500 | Server error | Exception dalam processing |

## Database Updates

Saat payment success diterima:

1. ✅ Update `payment_transactions`:
   - `payment_status` → 'paid'
   - `paid_at` → current timestamp

2. ✅ Update `companies`:
   - `package_id` → paket yang dibeli
   - `subscription_date` → sekarang (jika baru)
   - `subscription_expired_at` → sekarang + duration_months
   - `is_verified` → true
   - `verified_at` → sekarang (jika baru)

3. ✅ Renewal Logic:
   - Jika subscription masih aktif: tambah duration ke expiry date sekarang
   - Jika subscription sudah expired: mulai fresh dari sekarang

## Logging

Semua event dicatat di `storage/logs/laravel.log`:

```
[INFO] Webhook received: {"event":"payment.success",...}
[INFO] Payment confirmed for transaction: TRX-XXXX
[INFO] Transaction already paid: TRX-YYYY (idempotency)
[WARNING] Invalid webhook signature
[WARNING] Transaction not found: TRX-ZZZZ
[ERROR] Webhook error: Payment processing failed
```

## Production Checklist

- [x] Route accepts GET dan POST
- [x] Payload parsing untuk 2 format
- [x] Event type detection
- [x] Signature verification (optional)
- [x] Idempotency protection
- [x] Company subscription auto-update
- [x] Subscription renewal logic
- [x] Comprehensive logging
- [x] Error handling untuk semua kasus
- [x] CSRF protection excluded

## Known Issues & Solutions

**Issue: "Transaction not found"**
- Verify external_id match dengan transaction_number di database
- Check logs untuk melihat exact external_id yang dikirim

**Issue: "Payment status tidak update"**
- Check database: `SELECT * FROM payment_transactions WHERE transaction_number = 'TRX-XXX'`
- Verify company relationship
- Check logs untuk error detail

**Issue: "Halaman masih waiting setelah pembayaran"**
- Check webhook logs: apakah webhook diterima?
- Verify auto-refresh di waiting page berfungsi
- Test manual: `php artisan webhook:test-get`

## Next Steps

1. Integrate dengan real payment gateway ngrok URL
2. Monitor webhook logs untuk production issues
3. Setup email notification saat pembayaran sukses
4. Create admin dashboard untuk payment monitoring
