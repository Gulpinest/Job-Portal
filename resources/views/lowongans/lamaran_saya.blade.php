<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Daftar Lamaran Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">

                {{-- Quick Access Card untuk Jadwal Wawancara --}}
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-2xl shadow-lg border border-indigo-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-indigo-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Jadwal Wawancara
                            </h3>
                            <p class="text-sm text-indigo-700 mt-1">Lihat jadwal wawancara yang sudah dijadwalkan oleh perusahaan</p>
                        </div>
                        <a href="{{ route('pelamar.interviews.index') }}" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow-md whitespace-nowrap">
                            Lihat Jadwal →
                        </a>
                    </div>
                </div>

                {{-- Statistik Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Lamaran -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 font-medium text-sm">Total Lamaran</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalLamarans }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Diterima -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 font-medium text-sm">Diterima</p>
                                <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $diterimaaCount }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Diproses -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 font-medium text-sm">Diproses</p>
                                <p class="text-3xl font-extrabold text-indigo-600 mt-1">{{ $diproseswCount }}</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.91 8.91 0 0112 21a9 9 0 01-5.185-1.558L4 17m16-4h-5.582m0 0l-2.458 2.458M12 2l-.001 6"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Ditolak -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 font-medium text-sm">Ditolak</p>
                                <p class="text-3xl font-extrabold text-red-600 mt-1">{{ $ditolakCount }}</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Search & Filter Bar --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <form method="GET" action="{{ route('lowongans.lamaran_saya') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Search Input -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Lowongan</label>
                                <input type="text" name="search" placeholder="Cari berdasarkan posisi atau perusahaan..."
                                    value="{{ request('search') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition text-gray-900" />
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                                <select name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition text-gray-900">
                                    <option value="">-- Semua Status --</option>
                                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Accepted" {{ request('status') === 'Accepted' ? 'selected' : '' }}>Diterima</option>
                                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow-md">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Cari
                                </button>
                                <a href="{{ route('lowongans.lamaran_saya') }}"
                                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition shadow-sm text-center">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Lamaran List --}}
                <div class="space-y-4">
                    @forelse ($lamarans as $lamaran)
                        @php
                            // Tentukan warna dan ikon berdasarkan status lamaran
                            $status = $lamaran->status_ajuan ?? 'Pending';
                            $colorClass = '';
                            $iconPath = '';

                            switch ($status) {
                                case 'Accepted':
                                    $colorClass = 'bg-green-50 border-green-500 text-green-700 hover:border-green-600';
                                    $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                    break;
                                case 'Rejected':
                                    $colorClass = 'bg-red-50 border-red-500 text-red-700 hover:border-red-600';
                                    $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                    break;
                                case 'Pending':
                                default:
                                    $colorClass = 'bg-yellow-50 border-yellow-500 text-yellow-700 hover:border-yellow-600';
                                    $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856A2 2 0 0021 17L3 17a2 2 0 012-2z"></path>';
                                    break;
                            }
                        @endphp

                        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 {{ $colorClass }} transition duration-300 ease-in-out hover:shadow-xl">

                            <div class="flex justify-between items-start">

                                <!-- Detail Pekerjaan -->
                                <div class="flex-1 pr-4">
                                    <h3 class="text-xl font-extrabold text-gray-900 leading-tight">
                                        {{ $lamaran->lowongan->judul ?? 'Lowongan Tidak Ditemukan' }}
                                    </h3>

                                    <p class="text-md font-semibold text-indigo-600 mt-1">
                                        {{ $lamaran->lowongan->company->nama_perusahaan ?? 'Perusahaan Tidak Diketahui' }}
                                    </p>

                                    <p class="text-sm text-gray-600 mt-1">
                                        Posisi: {{ $lamaran->lowongan->posisi ?? '-' }}
                                    </p>
                                </div>

                                <!-- Status & Ikon -->
                                <div class="flex flex-col items-end flex-shrink-0">
                                    <div class="flex items-center space-x-2 px-3 py-1.5 rounded-full text-sm font-bold shadow-md {{ $colorClass }}">

                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $iconPath !!}</svg>
                                        <span>
                                            @if ($status === 'Accepted')
                                                Diterima
                                            @elseif ($status === 'Rejected')
                                                Ditolak
                                            @else
                                                Menunggu
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Dilamar: {{ $lamaran->created_at->format('d M Y') }}</p>
                                </div>

                            </div>

                            <hr class="my-4 border-gray-100">

                            <div class="flex justify-between items-center flex-wrap gap-3">

                                <!-- Detail Resume -->
                                <div class="flex items-center text-sm text-gray-700">
                                    <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Resume: <span class="font-medium ml-1 text-gray-900">{{ $lamaran->resume->nama_resume ?? 'Resume Dihapus' }}</span>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="flex gap-2">
                                    <a href="{{ route('lowongans.detail', $lamaran->lowongan->id_lowongan ?? '#') }}"
                                        class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-100 transition ease-in-out duration-150 shadow-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat Detail
                                    </a>

                                    @if ($status === 'Pending')
                                        <form method="POST" action="{{ route('lamaran.withdraw', $lamaran->id_lamaran) }}" class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin mencabut lamaran ini? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-300 rounded-xl font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-100 transition ease-in-out duration-150 shadow-sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Cabut Lamaran
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-lg text-center">
                            <p class="text-gray-900 font-semibold text-lg">Anda belum melamar pekerjaan apa pun saat ini.</p>
                            <a href="{{ route('lowongans.pelamar_index') }}"
                                class="text-indigo-600 hover:text-indigo-700 font-medium mt-3 inline-block">
                                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                Jelajahi Lowongan Tersedia
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($lamarans->hasPages())
                    <div class="mt-8 space-y-4">
                        <div class="text-sm text-gray-600 text-center">
                            Menampilkan <span class="font-semibold">{{ $lamarans->firstItem() }}</span>
                            hingga
                            <span class="font-semibold">{{ $lamarans->lastItem() }}</span>
                            dari
                            <span class="font-semibold">{{ $lamarans->total() }}</span>
                            lamaran
                        </div>
                        <div class="flex justify-center">
                            {{ $lamarans->withQueryString()->links() }}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
