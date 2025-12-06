<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Jadwal Wawancara Saya') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 text-sm bg-green-100 text-green-800 border border-green-400 rounded-2xl shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <!-- Total Interviews -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Total Wawancara</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $totalInterviews }}</p>
                </div>

                <!-- Upcoming -->
                <div class="bg-blue-50 rounded-2xl p-6 shadow-lg border border-blue-200">
                    <p class="text-sm font-medium text-blue-700">Akan Datang</p>
                    <p class="text-3xl font-extrabold text-blue-800 mt-1">{{ $upcomingInterviews }}</p>
                </div>

                <!-- Completed -->
                <div class="bg-green-50 rounded-2xl p-6 shadow-lg border border-green-200">
                    <p class="text-sm font-medium text-green-700">Selesai</p>
                    <p class="text-3xl font-extrabold text-green-800 mt-1">{{ $completedInterviews }}</p>
                </div>

                <!-- Cancelled -->
                <div class="bg-red-50 rounded-2xl p-6 shadow-lg border border-red-200">
                    <p class="text-sm font-medium text-red-700">Dibatalkan</p>
                    <p class="text-3xl font-extrabold text-red-800 mt-1">{{ $cancelledInterviews }}</p>
                </div>

            </div>

            <!-- Search & Filter -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-8">
                <form method="GET" action="{{ route('pelamar.interviews.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" placeholder="Cari nama perusahaan atau lowongan..."
                        value="{{ request('search') }}"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">

                    <select name="status" class="px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">
                        <option value="">Semua Status</option>
                        <option value="Scheduled" @selected(request('status') === 'Scheduled')>Dijadwalkan</option>
                        <option value="Completed" @selected(request('status') === 'Completed')>Selesai</option>
                        <option value="Cancelled" @selected(request('status') === 'Cancelled')>Dibatalkan</option>
                    </select>

                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-md">
                        Cari
                    </button>
                    <a href="{{ route('pelamar.interviews.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition">
                        Reset
                    </a>
                </form>
            </div>

            <!-- Interviews List -->
            <div class="space-y-4">
                @forelse($interviews as $interview)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl hover:border-indigo-300 transition p-6">
                        <div class="flex flex-col lg:flex-row justify-between gap-4">

                            <!-- Left: Company & Job Info -->
                            <div class="flex-1">
                                <div class="flex gap-4 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($interview->lowongan->company->nama_perusahaan ?? 'C', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $interview->lowongan->company->nama_perusahaan }}</h3>
                                        <p class="text-sm font-semibold text-indigo-600">{{ $interview->lowongan->judul }}</p>
                                    </div>
                                </div>

                                <!-- Interview Details -->
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-600 font-medium">Tanggal & Waktu</p>
                                        <p class="text-gray-900 font-semibold mt-1">
                                            @if($interview->waktu_jadwal)
                                                {{ $interview->waktu_jadwal->format('d M Y H:i') }}
                                            @else
                                                <span class="text-gray-400">Belum ditentukan</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium">Tipe</p>
                                        <p class="mt-1">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $interview->type === 'Online' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                                {{ $interview->type ?? 'N/A' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium">Lokasi</p>
                                        <p class="text-gray-900 font-semibold mt-1 truncate">{{ $interview->lokasi ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Status & Action -->
                            <div class="lg:w-64 flex flex-col items-end gap-3">
                                @php
                                    $statusColor = match($interview->status) {
                                        'Scheduled' => 'bg-blue-100 text-blue-700 border-blue-300',
                                        'Completed' => 'bg-green-100 text-green-700 border-green-300',
                                        'Cancelled' => 'bg-red-100 text-red-700 border-red-300',
                                        default => 'bg-gray-100 text-gray-700 border-gray-300'
                                    };
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusColor }}">
                                    {{ $interview->status }}
                                </span>

                                <a href="{{ route('pelamar.interviews.show', $interview) }}" class="w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition text-center text-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada jadwal wawancara</h3>
                        <p class="text-gray-600">Perusahaan akan segera menjadwalkan wawancara Anda.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($interviews->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $interviews->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
