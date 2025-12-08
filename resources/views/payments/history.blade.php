<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Riwayat Transaksi Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">

                <!-- Header Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Total Transactions -->
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                        <p class="text-sm font-semibold text-blue-600 uppercase">Total Transaksi</p>
                        <p class="text-3xl font-bold text-blue-900 mt-2">{{ $transactions->total() }}</p>
                    </div>

                    <!-- Paid Transactions -->
                    <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                        <p class="text-sm font-semibold text-green-600 uppercase">Pembayaran Selesai</p>
                        <p class="text-3xl font-bold text-green-900 mt-2">
                            {{ \App\Models\PaymentTransaction::where('company_id', $company->id_company)->where('payment_status', 'completed')->count() }}
                        </p>
                    </div>

                    <!-- Pending Transactions -->
                    <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-200">
                        <p class="text-sm font-semibold text-yellow-600 uppercase">Menunggu Pembayaran</p>
                        <p class="text-3xl font-bold text-yellow-900 mt-2">
                            {{ \App\Models\PaymentTransaction::where('company_id', $company->id_company)->where('payment_status', 'pending')->count() }}
                        </p>
                    </div>
                </div>

                <!-- Transaction List -->
                <div class="overflow-x-auto">
                    @if($transactions->count() > 0)
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="bg-gray-100 border-b border-gray-200 text-gray-900 font-semibold">
                                <tr>
                                    <th class="px-6 py-4">No. Transaksi</th>
                                    <th class="px-6 py-4">Paket</th>
                                    <th class="px-6 py-4">Jumlah</th>
                                    <th class="px-6 py-4">Durasi</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-xs font-semibold text-gray-900">
                                                {{ $transaction->transaction_number }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-gray-900">
                                                {{ $transaction->package->nama_package }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-gray-900">
                                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($transaction->package->duration_months)
                                                {{ $transaction->package->duration_months }} Bulan
                                            @else
                                                Selamanya
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full 
                                                @if($transaction->payment_status === 'completed')
                                                    bg-green-100 text-green-800
                                                @elseif($transaction->payment_status === 'pending')
                                                    bg-yellow-100 text-yellow-800
                                                @elseif($transaction->payment_status === 'expired')
                                                    bg-red-100 text-red-800
                                                @else
                                                    bg-gray-100 text-gray-800
                                                @endif
                                            ">
                                                @switch($transaction->payment_status)
                                                    @case('completed')
                                                        ✓ Selesai
                                                        @break
                                                    @case('pending')
                                                        ⏳ Menunggu
                                                        @break
                                                    @case('expired')
                                                        ✕ Expired
                                                        @break
                                                    @default
                                                        {{ ucfirst($transaction->payment_status) }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-600">
                                                <p class="font-semibold">{{ $transaction->created_at->format('d M Y') }}</p>
                                                <p class="text-gray-500">{{ $transaction->created_at->format('H:i') }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                @if($transaction->payment_status === 'completed')
                                                    <a href="{{ route('payments.success', $transaction) }}" 
                                                        class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs">
                                                        Detail
                                                    </a>
                                                @elseif($transaction->payment_status === 'pending')
                                                    <a href="{{ route('payments.waiting', $transaction) }}" 
                                                        class="text-yellow-600 hover:text-yellow-900 font-semibold text-xs">
                                                        Bayar
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mb-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">Belum ada transaksi</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai berlangganan sekarang untuk mendapatkan akses penuh.</p>
                            <a href="{{ route('payments.packages') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700">
                                Pilih Paket
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Back Button -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('company.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
