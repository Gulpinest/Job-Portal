<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Dashboard Perusahaan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Status Langganan Anda</h2>
            
            @if ($is_active)
                <div class="p-4 border-l-4 border-indigo-500 bg-indigo-50">
                    <p class="text-xl font-semibold text-indigo-700">
                        Paket Aktif: 
                        {{ $company->package->name ?? 'N/A' }} 
                    </p>
                    <p class="text-gray-600 mt-1">
                        @if ($company->job_quota > 9000)
                            Kuota Lowongan Tersisa: <span class="font-bold text-lg">Tidak Terbatas</span>
                        @else
                            Kuota Lowongan Tersisa: <span class="font-bold text-lg">{{ $company->job_quota }}</span>
                        @endif
                    </p>
                    <p class="text-gray-600">
                        Berakhir Pada: 
                        <span class="font-bold">{{ $company->subscription_ends_at->format('d M Y, H:i') }}</span>
                    </p>
                </div>
            @else
                <div class="p-4 border-l-4 border-red-500 bg-red-50">
                    <p class="text-xl text-red-700 font-semibold">
                        Tidak ada paket aktif.
                    </p>
                    <p class="text-gray-600 mt-2">
                        Kuota Lowongan Tersisa: {{ $company->job_quota }}
                    </p>
                    <a href="{{ route('langganan.index') }}" class="mt-3 inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition duration-150">
                        Beli Paket Sekarang
                    </a>
                </div>
            @endif
        </div>
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
        </div>
    </div>
</x-app-layout>
