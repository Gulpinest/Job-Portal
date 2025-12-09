<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Detail Jadwal Wawancara') }}
            </h2>
            <a href="{{ route('pelamar.interviews.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column: Main Info -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Interview Schedule Card -->
                    <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 border-b pb-4">Jadwal Wawancara</h3>

                        <div class="space-y-4">
                            <!-- Tanggal & Waktu -->
                            <div class="flex justify-between items-start p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                                <span class="text-sm font-medium text-indigo-700">Tanggal & Waktu</span>
                                <span class="text-right">
                                    <p class="font-semibold text-gray-900">
                                        @if($interview->waktu_jadwal)
                                            {{ $interview->waktu_jadwal->format('d M Y') }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-indigo-600 font-medium">
                                        @if($interview->waktu_jadwal)
                                            {{ $interview->waktu_jadwal->format('H:i') }} WIB
                                        @endif
                                    </p>
                                </span>
                            </div>

                            <!-- Tipe Wawancara -->
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Tipe Wawancara</span>
                                <span class="inline-flex items-center gap-2">
                                    @if(($interview->type ?? '') === 'Online')
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20m0 0l-.75 3M9 20H5m4-2.972A4.002 4.002 0 005 15m14 0h4m0 0l.75 3M19 20l-.75-3m0 0a4.002 4.002 0 01-8 .972m0 0H5"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $interview->type ?? 'N/A' }}</span>
                                </span>
                            </div>

                            <!-- Lokasi / Link -->
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-600 mb-2">Lokasi / Link</p>
                                <p class="font-medium text-gray-900 break-all text-sm">{{ $interview->lokasi ?? '-' }}</p>
                            </div>

                            <!-- Catatan -->
                            @if ($interview->catatan)
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-sm font-medium text-blue-700 mb-2">Catatan Perusahaan</p>
                                    <p class="text-sm text-blue-900 whitespace-pre-wrap">{{ $interview->catatan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Lowongan Information -->
                    <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-4">Lowongan Kerja</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Judul</span>
                                <span class="font-medium text-gray-900">{{ $interview->lowongan->judul }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Posisi</span>
                                <span class="font-medium text-gray-900">{{ $interview->lowongan->posisi }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Gaji</span>
                                <span class="font-medium text-gray-900">{{ $interview->lowongan->gaji ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Status & Company Info -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Status Card -->
                    <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wide">Status</h3>

                        @php
                            $statusColor = match($interview->status) {
                                'Scheduled' => 'bg-blue-50 border-blue-200 text-blue-700',
                                'Completed' => 'bg-green-50 border-green-200 text-green-700',
                                'Cancelled' => 'bg-red-50 border-red-200 text-red-700',
                                default => 'bg-gray-50 border-gray-200 text-gray-700'
                            };
                            $statusLabel = match($interview->status) {
                                'Scheduled' => 'Dijadwalkan',
                                'Completed' => 'Selesai',
                                'Cancelled' => 'Dibatalkan',
                                default => $interview->status
                            };
                            $statusIcon = match($interview->status) {
                                'Scheduled' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                                'Completed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                'Cancelled' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                                default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                            };
                        @endphp

                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ $statusColor }} border w-full justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcon }}"></path>
                            </svg>
                            <span class="font-semibold">{{ $statusLabel }}</span>
                        </div>

                        <!-- Time Countdown -->
                        @if ($interview->status === 'Scheduled' && $interview->waktu_jadwal && $interview->waktu_jadwal->isFuture())
                            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                <p class="text-xs font-semibold text-amber-700 mb-1">Waktu Tersisa</p>
                                <p class="text-sm text-amber-700">{{ $interview->waktu_jadwal->diffForHumans() }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Company Information -->
                    <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 mb-4 uppercase tracking-wide">Perusahaan</h3>

                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Nama</p>
                                <p class="font-medium text-gray-900 text-sm">{{ $interview->lowongan->company->nama_perusahaan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Email</p>
                                <p class="font-medium text-gray-900 text-sm break-all">{{ $interview->lowongan->company->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Telepon</p>
                                <p class="font-medium text-gray-900 text-sm">{{ $interview->lowongan->company->no_telp_perusahaan ?? '-' }}</p>
                            </div>
                            @if($interview->lowongan->company->alamat_perusahaan)
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Alamat</p>
                                    <p class="font-medium text-gray-900 text-sm">{{ $interview->lowongan->company->alamat_perusahaan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
