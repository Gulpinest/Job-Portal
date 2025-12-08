<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg shadow-xl p-8 border border-gray-100">

                <div class="text-center mb-8">
                    <div class="text-5xl mb-4">⏳</div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Menunggu Konfirmasi Pembayaran</h1>
                    <p class="text-gray-600">Silakan lakukan transfer sesuai nomor virtual account di bawah</p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Pembayaran</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-yellow-200">
                            <span class="text-gray-700">No. Referensi:</span>
                            <span class="font-mono font-semibold text-gray-900">{{ $transaction->transaction_number }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-yellow-200">
                            <span class="text-gray-700">Nomor Virtual Account:</span>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-2xl font-bold text-yellow-600">{{ $transaction->va_number }}</span>
                                <button type="button" onclick="copyToClipboard('{{ $transaction->va_number }}')" class="text-sm bg-yellow-200 hover:bg-yellow-300 px-2 py-1 rounded">
                                    Salin
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-yellow-200">
                            <span class="text-gray-700">Jumlah Transfer:</span>
                            <span class="text-2xl font-bold text-yellow-600">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-yellow-200">
                            <span class="text-gray-700">Waktu Kadaluarsa:</span>
                            <span class="font-semibold text-red-600">
                                {{ $transaction->expired_at->format('d M Y - H:i') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center pt-3">
                            <span class="text-gray-700">Status:</span>
                            <span class="inline-block bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                                ⏳ Menunggu Pembayaran
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                    <p class="text-sm text-blue-700 mb-2">
                        <strong>💡 Petunjuk:</strong>
                    </p>
                    <ul class="text-sm text-blue-600 space-y-1 list-disc list-inside">
                        <li>Transfer ke nomor virtual account di atas dengan nominal tepat sesuai</li>
                        <li>Pembayaran akan dikonfirmasi secara otomatis dalam beberapa menit</li>
                        <li>Pastikan nominal transfer sesuai, jangan kurang atau lebih</li>
                    </ul>
                </div>

                <div class="flex gap-4 mb-6">
                    <a href="{{ route('payments.packages') }}"
                        class="flex-1 text-center bg-gray-200 text-gray-700 font-bold py-3 px-4 rounded-lg hover:bg-gray-300 transition">
                        Kembali ke Paket
                    </a>
                    
                    <a href="{{ $transaction->payment_url }}" target="_blank" rel="noopener noreferrer"
                        class="flex-1 text-center bg-green-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-700 transition">
                        💳 Bayar Sekarang
                    </a>
                    
                    <button type="button" onclick="checkPaymentStatus()"
                        class="flex-1 bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition">
                        Cek Status Pembayaran
                    </button>
                </div>

                <p class="text-center text-gray-500 text-xs">
                    Halaman akan auto-refresh setiap 5 detik untuk mengecek status pembayaran
                </p>

            </div>
        </div>
    </div>

    <script>
        let checkCount = 0;
        
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Nomor VA berhasil disalin!');
            });
        }

        function checkPaymentStatus() {
            const transactionId = '{{ $transaction->id }}';
            const url = `/payments/${transactionId}/check-status`;
            
            console.log(`[${new Date().toLocaleTimeString()}] Checking status for transaction:`, transactionId);
            console.log('URL:', url);
            
            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    if (data.is_paid) {
                        console.log('✓ Payment detected as PAID! Redirecting to success page...');
                        window.location.href = '{{ route("payments.success", $transaction) }}';
                    } else if (data.is_expired) {
                        alert('Pembayaran telah kadaluarsa. Silakan buat transaksi baru.');
                        window.location.href = '{{ route("payments.packages") }}';
                    } else {
                        console.log('Status: Masih menunggu pembayaran (Status: ' + data.status + ')');
                    }
                })
                .catch(error => {
                    console.error('Error checking status:', error);
                    console.error('Error details:', error.message);
                });
        }

        // Auto-refresh status every 3 seconds (more frequent)
        const autoCheckInterval = setInterval(() => {
            checkCount++;
            const transactionId = '{{ $transaction->id }}';
            const url = `/payments/${transactionId}/check-status`;
            
            console.log(`[AUTO-CHECK #${checkCount}] ${new Date().toLocaleTimeString()}`);
            
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        console.warn('Response not ok:', response.status);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(`[AUTO-CHECK #${checkCount}] Response:`, data);
                    
                    if (data.is_paid) {
                        console.log(`[AUTO-CHECK #${checkCount}] ✓✓✓ PAYMENT DETECTED! Redirecting...`);
                        clearInterval(autoCheckInterval);
                        window.location.href = '{{ route("payments.success", $transaction) }}';
                    } else if (data.is_expired) {
                        console.log(`[AUTO-CHECK #${checkCount}] Payment expired, redirecting...`);
                        clearInterval(autoCheckInterval);
                        window.location.href = '{{ route("payments.packages") }}';
                    }
                })
                .catch(error => {
                    console.warn(`[AUTO-CHECK #${checkCount}] Error:`, error.message);
                });
        }, 3000);

        // Also check on page visibility change
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                console.log('Page visible again, checking status immediately...');
                checkPaymentStatus();
            }
        });

        // Initial check when page loads
        console.log('Waiting page loaded. Transaction ID: {{ $transaction->id }}');
        console.log('Auto-checking every 3 seconds...');
    </script>
</x-app-layout>
