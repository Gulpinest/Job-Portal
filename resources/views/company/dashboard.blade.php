<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Dashboard Perusahaan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- VERIFICATION ALERT (Show only if not verified) -->
            @if(!$company->is_verified)
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl shadow-lg border-2 border-amber-200 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-amber-100 rounded-full -mr-20 -mt-20 opacity-30"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-amber-100">
                                <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.08 6.47a.75.75 0 00-1.06 0l-1.5 1.5a.75.75 0 001.06 1.06l1.5-1.5a.75.75 0 000-1.06zM17 8.25a.75.75 0 100-1.5.75.75 0 000 1.5zM3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-amber-900">⏳ Akun Menunggu Verifikasi Admin</h3>
                            <p class="text-sm text-amber-800 mt-2">
                                Profil perusahaan Anda sedang dalam proses verifikasi oleh admin kami. Anda akan dapat:
                            </p>
                            <ul class="mt-3 space-y-2 text-sm text-amber-800">
                                <li class="flex items-center gap-2">
                                    <span class="inline-block w-2 h-2 bg-amber-600 rounded-full"></span>
                                    <span>📋 Membuat dan mempublikasikan lowongan pekerjaan</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="inline-block w-2 h-2 bg-amber-600 rounded-full"></span>
                                    <span>💰 Membeli paket berlangganan premium</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="inline-block w-2 h-2 bg-amber-600 rounded-full"></span>
                                    <span>👥 Mengelola pelamar dan jadwal wawancara</span>
                                </li>
                            </ul>
                            <p class="text-xs text-amber-700 font-semibold mt-4">
                                💡 Tip: Lengkapi semua informasi profil perusahaan untuk mempercepat proses verifikasi.
                            </p>
                        </div>

                        <div class="flex-shrink-0">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-amber-600 text-white font-semibold text-sm hover:bg-amber-700 transition duration-300 shadow-md">
                                Lihat Profil
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 1. HEADER & STATUS -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">Halo, {{ $company->nama_perusahaan }}!</h3>
                <p class="text-md text-gray-500">Dashboard manajemen lowongan dan pelamar Anda.</p>

                {{-- Verification Status --}}
                <div class="mt-4 p-3 rounded-xl flex justify-between items-center {{ $company->is_verified ? 'bg-green-50 border border-green-200' : 'bg-amber-50 border border-amber-200' }}">
                    <div>
                        <span class="text-sm font-medium {{ $company->is_verified ? 'text-green-700' : 'text-amber-700' }}">
                            Status Verifikasi:
                            @if ($company->is_verified)
                                <span class="font-semibold">✓ Terverifikasi</span>
                                <span class="text-xs text-green-600 ml-2">({{ $company->verified_at->format('d M Y') }})</span>
                            @else
                                <span class="font-semibold">⏳ Menunggu Persetujuan</span>
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Active Package & Subscription Status --}}
                <div class="mt-4 p-6 rounded-xl {{ $company->isSubscriptionActive() ? 'bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200' : 'bg-gradient-to-r from-orange-50 to-yellow-50 border border-orange-200' }}">
                    <div class="space-y-4">
                        <!-- Header -->
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold {{ $company->isSubscriptionActive() ? 'text-indigo-600' : 'text-orange-600' }} uppercase">📦 Status Paket Langganan</p>
                            </div>
                            <a href="{{ route('payments.packages') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 underline">
                                Ubah Paket →
                            </a>
                        </div>

                        <!-- Package Info -->
                        @if($company->package)
                            <!-- Package Name & Type -->
                            <div class="flex items-center gap-3">
                                <div class="text-2xl">
                                    @if($company->package->price == 0)
                                        🎁
                                    @else
                                        ⭐
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium {{ $company->isSubscriptionActive() ? 'text-indigo-600' : 'text-orange-600' }}">
                                        {{ $company->package->price == 0 ? 'PAKET GRATIS' : 'PAKET PREMIUM' }}
                                    </p>
                                    <p class="text-2xl font-bold {{ $company->isSubscriptionActive() ? 'text-indigo-900' : 'text-orange-900' }} mt-1">
                                        {{ $company->package->nama_package }}
                                    </p>
                                </div>
                            </div>

                            <!-- Package Details Grid -->
                            <div class="grid grid-cols-3 gap-3">
                                <!-- Harga -->
                                <div class="bg-white bg-opacity-50 rounded-lg p-3">
                                    <p class="text-xs font-semibold {{ $company->isSubscriptionActive() ? 'text-indigo-600' : 'text-orange-600' }} uppercase">Harga</p>
                                    <p class="text-lg font-bold {{ $company->isSubscriptionActive() ? 'text-indigo-900' : 'text-orange-900' }} mt-1">
                                        @if($company->package->price == 0)
                                            Gratis
                                        @else
                                            Rp {{ number_format($company->package->price, 0, ',', '.') }}
                                        @endif
                                    </p>
                                </div>

                                <!-- Sisa Durasi -->
                                <div class="bg-white bg-opacity-50 rounded-lg p-3">
                                    <p class="text-xs font-semibold {{ $company->isSubscriptionActive() ? 'text-indigo-600' : 'text-orange-600' }} uppercase">Sisa Durasi</p>
                                    <p class="text-lg font-bold {{ $company->isSubscriptionActive() ? 'text-indigo-900' : 'text-orange-900' }} mt-1">
                                        @if($company->subscription_expired_at && $company->isSubscriptionActive())
                                            {{ $company->getRemainingDurationMonths() }} Bulan
                                        @elseif($company->package->duration_months)
                                            {{ $company->package->duration_months }} Bulan
                                        @else
                                            Selamanya
                                        @endif
                                    </p>
                                </div>

                                <!-- Status -->
                                <div class="bg-white bg-opacity-50 rounded-lg p-3">
                                    <p class="text-xs font-semibold {{ $company->isSubscriptionActive() ? 'text-indigo-600' : 'text-orange-600' }} uppercase">Status</p>
                                    <p class="text-lg font-bold mt-1">
                                        @if($company->isSubscriptionActive())
                                            <span class="text-green-600">✓ Aktif</span>
                                        @else
                                            <span class="text-orange-600">⏳ Expired</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Subscription Timeline -->
                            @if($company->subscription_date || $company->subscription_expired_at)
                            <div class="bg-white bg-opacity-60 rounded-lg p-3 border {{ $company->isSubscriptionActive() ? 'border-indigo-200' : 'border-orange-200' }}">
                                <p class="text-xs font-semibold {{ $company->isSubscriptionActive() ? 'text-indigo-600' : 'text-orange-600' }} uppercase mb-2">Periode Langganan</p>
                                <div class="flex items-center justify-between text-sm">
                                    <div>
                                        <p class="text-xs text-gray-600">Mulai</p>
                                        <p class="font-semibold text-gray-900">{{ $company->subscription_date ? $company->subscription_date->format('d M Y') : '-' }}</p>
                                    </div>
                                    <div class="text-2xl text-gray-400">→</div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-600">Berakhir</p>
                                        <p class="font-semibold {{ $company->isSubscriptionActive() ? 'text-green-700' : 'text-red-700' }}">
                                            @if($company->subscription_expired_at)
                                                {{ $company->subscription_expired_at->format('d M Y') }}
                                                @if($company->isSubscriptionActive())
                                                    <br><span class="text-xs font-normal">{{ now()->diffInDays($company->subscription_expired_at) }} hari lagi</span>
                                                @endif
                                            @else
                                                Tidak ada
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Action Button -->
                            @if($company->isSubscriptionActive())
                            <div class="bg-white bg-opacity-60 rounded-lg p-3 border border-indigo-200">
                                <p class="text-sm font-semibold text-green-700">
                                    ✓ Langganan Anda aktif dan berfungsi dengan baik
                                </p>
                            </div>
                            @else
                            <div class="bg-white bg-opacity-60 rounded-lg p-3 border border-orange-200">
                                <a href="{{ route('payments.packages') }}" class="text-sm font-semibold text-orange-700 hover:text-orange-900 underline">
                                    → Perpanjang langganan sekarang
                                </a>
                            </div>
                            @endif
                        @else
                            <div class="bg-white bg-opacity-60 rounded-lg p-4 border border-gray-200 text-center">
                                <p class="text-sm text-gray-600 mb-3">Belum ada paket yang aktif</p>
                                <a href="{{ route('payments.packages') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900">
                                    Pilih Paket Sekarang →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 2. STATISTICS SUMMARY CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Card 1: Total Lowongans --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Total Lowongan</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $totalLowongans }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="text-green-600 font-semibold">{{ $activeLowongans }}</span> aktif,
                        <span class="text-red-600 font-semibold">{{ $closedLowongans }}</span> ditutup
                    </p>
                </div>

                {{-- Card 2: Active Lowongans --}}
                <div class="bg-green-50 rounded-2xl p-6 shadow-md border border-green-200">
                    <p class="text-sm font-medium text-green-700">Lowongan Aktif</p>
                    <p class="text-3xl font-extrabold text-green-800 mt-1">{{ $activeLowongans }}</p>
                    <a href="{{ route('lowongans.index') }}" class="text-xs text-green-600 hover:text-green-800 font-semibold mt-2 inline-block">Lihat semua →</a>
                </div>

                {{-- Card 3: Total Pelamar --}}
                <div class="bg-purple-50 rounded-2xl p-6 shadow-md border border-purple-200">
                    <p class="text-sm font-medium text-purple-700">Total Pelamar</p>
                    <p class="text-3xl font-extrabold text-purple-800 mt-1">{{ $totalPelamar }}</p>
                    <p class="text-xs text-purple-600 mt-2">
                        <span class="font-semibold">{{ $pendingPelamar }}</span> menunggu review
                    </p>
                </div>

                {{-- Card 4: Pending Review --}}
                <div class="bg-amber-50 rounded-2xl p-6 shadow-md border border-amber-200">
                    <p class="text-sm font-medium text-amber-700">Perlu Ditinjau</p>
                    <p class="text-3xl font-extrabold text-amber-800 mt-1">{{ $pendingPelamar }}</p>
                    <p class="text-xs text-amber-600 mt-2">Lamaran dengan status pending</p>
                </div>
            </div>

            <!-- 3. MAIN CONTENT: Quick Actions & Recent Activities -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left Column: Quick Actions --}}
                <div class="lg:col-span-1 space-y-6">

                    <!-- Quick Action Buttons -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 space-y-3">
                        <h4 class="text-lg font-bold text-gray-900 border-b pb-3">Aksi Cepat</h4>
                        <a href="{{ route('lowongans.create') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-700 transition shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Buat Lowongan Baru
                        </a>
                        <a href="{{ route('lowongans.index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-blue-700 transition shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Kelola Lowongan
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 hover:bg-gray-200 transition shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Profil
                        </a>
                    </div>

                    <!-- Company Info Card -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 space-y-4">
                        <h4 class="text-lg font-bold text-gray-900 border-b pb-3">Informasi Perusahaan</h4>

                        <div class="space-y-3 text-sm">
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-600 font-semibold">NAMA PERUSAHAAN</p>
                                <p class="text-gray-900 font-medium mt-1">{{ $company->nama_perusahaan }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-600 font-semibold">KONTAK</p>
                                <p class="text-gray-900 font-medium mt-1">{{ $company->no_telp_perusahaan }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-600 font-semibold">ALAMAT</p>
                                <p class="text-gray-900 font-medium mt-1 text-xs">{{ $company->alamat_perusahaan }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Recent Activities --}}
                <div class="lg:col-span-2 space-y-6">

                    <!-- Recent Lowongans -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">Lowongan Terbaru</h4>

                        <div class="space-y-3">
                            @forelse($recentLowongans as $lowongan)
                                <div class="p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition duration-150">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h5 class="font-bold text-gray-900">{{ $lowongan->judul }}</h5>
                                            <p class="text-sm text-gray-600 mt-1">{{ $lowongan->posisi }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $lowongan->status == 'Open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $lowongan->status }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between mt-3 text-xs text-gray-500">
                                        <div class="flex gap-4">
                                            <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V8.5m-11-5h5m-5 4h5m-5 4h8"></path></svg>{{ $lowongan->lamarans_count }} pelamar</span>
                                        </div>
                                        <span class="text-gray-400">{{ $lowongan->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">Belum ada lowongan</p>
                            @endforelse
                        </div>

                        <a href="{{ route('lowongans.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 mt-5 block text-center">
                            Lihat Semua Lowongan
                        </a>
                    </div>

                    <!-- Recent Lamarans -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">Pelamar Terbaru</h4>

                        <div class="space-y-3">
                            @forelse($recentLamarans as $lamaran)
                                <div class="p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition duration-150">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h5 class="font-semibold text-gray-900">{{ $lamaran->pelamar->user->name ?? 'N/A' }}</h5>
                                            <p class="text-sm text-gray-600 mt-1">Melamar: <span class="font-medium">{{ $lamaran->lowongan->judul }}</span></p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <span class="inline-block px-2 py-0.5 rounded bg-gray-100 text-gray-700">{{ $lamaran->status_ajuan }}</span>
                                            </p>
                                        </div>
                                        <span class="text-xs font-medium text-gray-500 text-right">
                                            {{ $lamaran->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">Belum ada pelamar</p>
                            @endforelse
                        </div>

                        <a href="{{ route('company.lamarans.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 mt-5 block text-center">
                            Lihat Semua Pelamar
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. PAYMENT TRANSACTION HISTORY -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">📋 Riwayat Transaksi Pembayaran</h4>

                @php
                    $transactions = \App\Models\PaymentTransaction::where('company_id', $company->id_company)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                @endphp

                <div class="space-y-3">
                    @forelse($transactions as $transaction)
                        <div class="p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition duration-150">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <h5 class="font-semibold text-gray-900">{{ $transaction->package->nama_package }}</h5>
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
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">
                                        <strong>No. Transaksi:</strong> {{ $transaction->transaction_number }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Durasi:</strong> 
                                        @if($transaction->package->duration_months)
                                            {{ $transaction->package->duration_months }} Bulan
                                        @else
                                            Selamanya
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        {{ $transaction->created_at->format('d M Y H:i') }}
                                    </p>
                                    @if($transaction->payment_status === 'completed' && $transaction->paid_at)
                                    <p class="text-xs text-green-600 mt-1">
                                        Dibayar: {{ $transaction->paid_at->format('d M Y') }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">Belum ada riwayat transaksi</p>
                            <a href="{{ route('payments.packages') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 underline">
                                Mulai Berlangganan →
                            </a>
                        </div>
                    @endforelse
                </div>

                @if($transactions->count() > 0)
                <a href="{{ route('company.payment-history') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 mt-5 block text-center">
                    Lihat Semua Transaksi
                </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
