# Payment Gateway Integration - Alur Pembayaran

## Ringkasan Alur

```
User memilih paket 
  ↓
Confirm pembayaran (detail paket)
  ↓
Process pembayaran → Create VA di Payment Gateway
  ↓
Redirect ke halaman "Menunggu Konfirmasi" 
  ↓
Payment Gateway: User bayar via VA
  ↓
Payment Gateway: Kirim webhook ke /webhook/payment
  ↓
WebhookController: Verifikasi & proses
  ↓
Update company subscription + payment_status = 'paid'
  ↓
User: Halaman auto-refresh mendeteksi status 'paid'
  ↓
Redirect ke halaman "Sukses"
```

## Komponen

### 1. Models
- **PaymentTransaction**: Menyimpan data transaksi pembayaran
  - Relationships: belongsTo Company, belongsTo Package
  - Methods: isPaid(), isExpired(), isPending()

- **Company**: Ditambah field subscription
  - package_id (FK ke packages)
  - subscription_date
  - subscription_expired_at
  - is_verified
  - verified_at

- **Package**: Paket langganan
  - nama_package
  - price
  - duration_months
  - job_limit (null = unlimited)

### 2. Controllers

#### PaymentController
- `packages()` - Tampilkan daftar paket
- `confirm(Package)` - Tampilkan detail pembayaran
- `process(Package)` - Proses pembayaran
  - Create transaction record
  - Call payment gateway API
  - Redirect ke waiting page
- `waiting(PaymentTransaction)` - Halaman tunggu pembayaran
  - Auto-check status setiap 5 detik
- `checkStatus()` - AJAX endpoint untuk cek status
- `success()` - Halaman sukses (premium)
- `successFree()` - Halaman sukses (free)

#### WebhookController (NEW)
- `handlePayment()` - Entry point webhook
  - Verifikasi signature HMAC-SHA256
  - Route ke handler berdasarkan event type
- `handlePaymentSuccess()` - Proses pembayaran sukses
  - Update transaction status = 'paid'
  - Hitung subscription expiry baru
  - Update company subscription
- `handlePaymentFailed()` - Catat pembayaran gagal
- `handlePaymentExpired()` - Catat pembayaran expired

### 3. Routes

```php
// Public routes (under company middleware)
GET  /payments/packages              → PaymentController@packages
GET  /payments/{package}/confirm     → PaymentController@confirm
POST /payments/{package}/process     → PaymentController@process
GET  /payments/{transaction}/waiting → PaymentController@waiting
GET  /payments/{transaction}/check-status → PaymentController@checkStatus (AJAX)
GET  /payments/{transaction}/success → PaymentController@success
GET  /payments/{company}/success-free → PaymentController@successFree

// Public webhook (no auth)
POST /webhook/payment → WebhookController@handlePayment
```

### 4. Alur Detail Pembayaran Sukses

```
PaymentController.process()
  ↓
  1. Validasi package & company
  2. Jika free package: Aktifkan langsung → success-free
  3. Jika premium:
     - Create PaymentTransaction (status: pending)
     - Call API: POST /virtual-account/create
     - Simpan: va_number, payment_url
     - Redirect ke waiting page
  ↓
waiting.blade.php
  ↓
  1. Tampilkan VA number & amount
  2. Button: "Bayar Sekarang" → payment_url (buka di tab baru)
  3. Button: "Cek Status" → fetch /check-status (manual)
  4. Auto-refresh setiap 5 detik → fetch /check-status
  ↓
WebhookController.handlePayment()
  ↓
  1. Terima webhook dari payment gateway
  2. Verifikasi X-Signature header dengan HMAC-SHA256
  3. Jika signature valid:
     - Find transaction by external_id
     - Jika belum paid (idempotency check):
       * Update status = 'paid' + paid_at timestamp
       * Hitung subscription_expired_at baru
       * Update company package
     - Return 200 OK
  ↓
waiting.blade.php (auto-refresh)
  ↓
  1. checkStatus() detect is_paid = true
  2. Redirect ke /payments/{id}/success
  ↓
success.blade.php
  ↓
  Tampilkan: Selamat, paket berhasil diaktifkan!
```

## Konfigurasi .env

```env
PAYMENT_API_KEY=your-api-key
PAYMENT_BASE_URL=https://payment-dummy.doovera.com/api/v1
PAYMENT_WEBHOOK_SECRET=your-webhook-secret
PAYMENT_EXPIRED_HOURS=24
```

## Testing Webhook Lokal

Untuk testing lokal, gunakan tools seperti:
- **ngrok**: Expose localhost ke internet
  ```bash
  ngrok http 8000
  # Dapatkan URL: https://xxx.ngrok.io
  ```
- **Postman**: Simulasi webhook
  ```
  POST http://localhost:8000/webhook/payment
  Headers:
    X-Signature: hash_hmac('sha256', payload, secret)
  Body: {"event":"payment_success","external_id":"TRX-xxx","amount":1000}
  ```

## Troubleshooting

### 1. "Gagal membuat pembayaran"
- Cek PAYMENT_API_KEY dan PAYMENT_BASE_URL di .env
- Pastikan payment gateway API dapat diakses
- Cek logs: `storage/logs/laravel.log`

### 2. Halaman tetap "Menunggu Konfirmasi"
- Webhook mungkin tidak diterima
- Cek: `storage/logs/laravel.log` - cari "Webhook received"
- Verifikasi X-Signature di payment gateway dashboard
- Test endpoint: POST /webhook/payment secara manual

### 3. "Gagal mengecek status pembayaran"
- Buka DevTools (F12) → Console
- Lihat error message lebih detail
- Pastikan user sudah authenticated
- Pastikan transaction_id valid

### 4. Webhook tidak memproses
- Cek signature verification
- Pastikan payload JSON valid
- Cek external_id match dengan transaction_number

## Database Schema

### payment_transactions table
```sql
id, company_id (FK), package_id (FK), 
transaction_number (UNIQUE), amount, 
payment_status (enum: pending/paid/failed/expired),
va_number, payment_url, 
paid_at, expired_at, 
created_at, updated_at
```

### companies table (modified)
```sql
-- Added columns:
package_id (FK), subscription_date, subscription_expired_at,
is_verified, verified_at, rejection_reason
```

### packages table (modified)
```sql
-- Added columns:
nama_package, duration_months, job_limit (nullable = unlimited)
```

## Keamanan

1. **Signature Verification**
   - Setiap webhook di-verify dengan HMAC-SHA256
   - Secret key ada di PAYMENT_WEBHOOK_SECRET

2. **Idempotency**
   - Webhook yang diterima 2x akan diabaikan
   - Check: isPaid() sebelum update

3. **Authorization**
   - Webhook route tanpa middleware (public)
   - checkStatus() require authentication
   - Validasi company_id match dengan user

4. **Logging**
   - Semua webhook event di-log
   - Error detail tersimpan di logs
