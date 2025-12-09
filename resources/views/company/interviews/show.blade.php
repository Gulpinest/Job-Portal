<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Detail Wawancara') }}
            </h2>
            <a href="{{ route('interview-schedules.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                ← Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Success/Error Messages -->
            @if (session('success') || session('error'))
                <div class="mb-6 p-4 text-sm rounded-2xl {{ session('success') ? 'bg-green-100 text-green-800 border border-green-400' : 'bg-red-100 text-red-800 border border-red-400' }} shadow-md">
                    {{ session('success') ?? session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Interview Details -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Detail Wawancara</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                                <span class="text-gray-600 font-medium">Tanggal & Waktu:</span>
                                <span class="text-gray-900 font-semibold">{{ $interviewSchedule->waktu_jadwal->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                                <span class="text-gray-600 font-medium">Tipe:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $interviewSchedule->tipe === 'Online' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $interviewSchedule->tipe }}
                                </span>
                            </div>
                            <div class="flex justify-between items-start p-4 bg-gray-50 rounded-xl">
                                <span class="text-gray-600 font-medium">Lokasi/Link:</span>
                                <span class="text-gray-900 font-semibold text-right max-w-xs">{{ $interviewSchedule->lokasi }}</span>
                            </div>
                            @if ($interviewSchedule->catatan)
                                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                    <p class="text-sm font-semibold text-blue-700 mb-2">Catatan:</p>
                                    <p class="text-sm text-blue-600">{{ $interviewSchedule->catatan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Pelamar Information -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Informasi Lowongan</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Status Lowongan:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $interviewSchedule->lowongan->status === 'Open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $interviewSchedule->lowongan->status }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Total Pelamar Diterima:</span>
                                <span class="text-gray-900 font-semibold text-lg text-green-600">{{ $interviewSchedule->lamarans->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lowongan Information -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Lowongan</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Judul:</span>
                                <span class="text-gray-900 font-semibold">{{ $interviewSchedule->lowongan->judul }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Posisi:</span>
                                <span class="text-gray-900 font-semibold">{{ $interviewSchedule->lowongan->posisi }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Pelamar yang Mengikuti Wawancara -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                        @php
                            $lamaransCollection = $interviewSchedule->lowongan?->lamarans ?? collect([]);
                        @endphp
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Pelamar yang Mengikuti Wawancara ({{ $lamaransCollection->count() }})</h3>

                        @if($lamaransCollection->count() > 0)
                            <div class="space-y-3">
                                @foreach($lamaransCollection as $lamaran)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 transition">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">{{ $lamaran->pelamar->user->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $lamaran->pelamar->user->email }}</p>
                                        </div>
                                        <a href="{{ route('company.lamarans.show', $lamaran) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm ml-4">
                                            Lihat Lamaran
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 italic">Belum ada pelamar yang diterima untuk lowongan ini.</p>
                        @endif
                    </div>

                </div>

                <!-- Sidebar: Actions -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Status Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">Status</h3>

                        @php
                            $statusColor = match($interviewSchedule->status) {
                                'Scheduled' => 'bg-blue-100 text-blue-700 border-blue-300',
                                'Completed' => 'bg-green-100 text-green-700 border-green-300',
                                'Cancelled' => 'bg-red-100 text-red-700 border-red-300',
                                default => 'bg-gray-100 text-gray-700 border-gray-300'
                            };
                        @endphp

                        <div class="p-4 rounded-xl border-2 {{ $statusColor }} text-center">
                            <p class="font-bold text-lg">{{ $interviewSchedule->status }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 space-y-3">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-3">Tindakan</h3>

                        @if ($interviewSchedule->status === 'Scheduled')
                            <!-- Edit Button -->
                            <a href="{{ route('interview-schedules.edit', $interviewSchedule) }}" class="w-full px-4 py-3 bg-yellow-600 text-white font-bold rounded-xl hover:bg-yellow-700 transition shadow-md flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>

                            <!-- Mark Completed Button -->
                            <form method="POST" action="{{ route('interview-schedules.mark-completed', $interviewSchedule) }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-md flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Tandai Selesai
                                </button>
                            </form>

                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('interview-schedules.destroy', $interviewSchedule) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan wawancara ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition shadow-md flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Batalkan
                                </button>
                            </form>
                        @else
                            <p class="text-center text-gray-600 text-sm py-3">Wawancara telah {{ strtolower($interviewSchedule->status) }}</p>
                        @endif
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
