<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Jadwal Wawancara') }}
            </h2>
            <a href="{{ route('company.lamarans.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-xl transition shadow-md flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Kelola Lamaran
            </a>
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

                <!-- Total Schedules -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Total Wawancara</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $totalSchedules }}</p>
                </div>

                <!-- Upcoming -->
                <div class="bg-blue-50 rounded-2xl p-6 shadow-lg border border-blue-200">
                    <p class="text-sm font-medium text-blue-700">Akan Datang</p>
                    <p class="text-3xl font-extrabold text-blue-800 mt-1">{{ $upcomingSchedules }}</p>
                </div>

                <!-- Completed -->
                <div class="bg-green-50 rounded-2xl p-6 shadow-lg border border-green-200">
                    <p class="text-sm font-medium text-green-700">Selesai</p>
                    <p class="text-3xl font-extrabold text-green-800 mt-1">{{ $completedSchedules }}</p>
                </div>

                <!-- Cancelled -->
                <div class="bg-red-50 rounded-2xl p-6 shadow-lg border border-red-200">
                    <p class="text-sm font-medium text-red-700">Dibatalkan</p>
                    <p class="text-3xl font-extrabold text-red-800 mt-1">{{ $cancelledSchedules }}</p>
                </div>

            </div>

            <!-- Search & Filter -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-8">
                <form method="GET" action="{{ route('interview-schedules.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" placeholder="Cari nama pelamar atau email..."
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
                    <a href="{{ route('interview-schedules.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition">
                        Reset
                    </a>
                </form>
            </div>

            <!-- Interviews Table -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                @if($schedules->count() > 0)
                    <table class="min-w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Lowongan</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Posisi</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Tanggal & Waktu</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Tipe</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Status</th>
                                <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($schedules as $schedule)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm">
                                        <p class="font-semibold text-gray-900">{{ $schedule->lowongan->judul }}</p>
                                        <p class="text-xs text-gray-600">{{ $schedule->accepted_lamarans_count }} pelamar</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <p class="font-semibold text-gray-900">{{ $schedule->lowongan->posisi }}</p>
                                        <p class="text-xs text-gray-600">{{ $schedule->lowongan->lokasi_kantor }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">
                                        @if($schedule->waktu_jadwal)
                                            {{ $schedule->waktu_jadwal->format('d M Y H:i') }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ ($schedule->type ?? '') === 'Online' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                            {{ $schedule->type ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $statusColor = match($schedule->status) {
                                                'Scheduled' => 'bg-blue-100 text-blue-700 border-blue-300',
                                                'Completed' => 'bg-green-100 text-green-700 border-green-300',
                                                'Cancelled' => 'bg-red-100 text-red-700 border-red-300',
                                                default => 'bg-gray-100 text-gray-700 border-gray-300'
                                            };
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusColor }}">
                                            {{ $schedule->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('interview-schedules.show', $schedule) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                                                Lihat
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="bg-white px-6 py-4 border-t border-gray-200">
                        {{ $schedules->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <p class="text-gray-500 text-lg">Belum ada jadwal wawancara</p>
                        <a href="{{ route('company.lamarans.index') }}" class="mt-4 inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Jadwalkan Wawancara Pertama
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
