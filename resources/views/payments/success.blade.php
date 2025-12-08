<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg shadow-xl p-8 border border-gray-100">

                <div class="text-center mb-8">
                    <div class="text-6xl mb-4 animate-bounce">✅</div>
                    <h1 class="text-3xl font-bold text-green-600 mb-4">Pembayaran Berhasil!</h1>
                    <p class="text-gray-600">Langganan Anda telah diaktifkan</p>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Aktivasi</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-green-200">
                            <span class="text-gray-700">No. Transaksi:</span>
                            <span class="font-mono font-semibold text-gray-900">{{ $transaction->transaction_number }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-green-200">
                            <span class="text-gray-700">Paket:</span>
                            <span class="font-semibold text-gray-900">{{ $transaction->package->nama_package }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-green-200">
                            <span class="text-gray-700">Jumlah Dibayar:</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-green-200">
                            <span class="text-gray-700">Waktu Pembayaran:</span>
                            <span class="font-semibold text-gray-900">{{ $transaction->paid_at->format('d M Y - H:i') }}</span>
                        </div>

                        @if($transaction->package->duration_months)
                        <div class="flex justify-between items-center pb-3 border-b border-green-200">
                            <span class="text-gray-700">Berlaku Hingga:</span>
                            <span class="font-semibold text-green-600">
                                {{ $transaction->company->subscription_expired_at->format('d M Y') }}
                            </span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center pt-3">
                            <span class="text-gray-700">Status:</span>
                            <span class="inline-block bg-green-200 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                ✓ Aktif
                            </span>
                        </div>
                    </div>
                </div>

                @if($transaction->package->job_limit)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                    <p class="text-sm text-blue-700">
                        <strong>📋 Limit Lowongan:</strong> Anda dapat membuat hingga <strong>{{ $transaction->package->job_limit }}</strong> lowongan aktif secara bersamaan
                    </p>
                </div>
                @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                    <p class="text-sm text-blue-700">
                        <strong>📋 Limit Lowongan:</strong> Anda dapat membuat <strong>lowongan tanpa batas</strong>
                    </p>
                </div>
                @endif

                <div class="flex gap-4">
                    <a href="{{ route('company.dashboard') }}"
                        class="flex-1 text-center bg-gray-200 text-gray-700 font-bold py-3 px-4 rounded-lg hover:bg-gray-300 transition">
                        Kembali ke Dashboard
                    </a>
                    
                    <a href="{{ route('lowongans.index') }}"
                        class="flex-1 text-center bg-green-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-700 transition">
                        Mulai Posting Lowongan
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
