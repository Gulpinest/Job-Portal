<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Detail Jadwal Wawancara') }}
            </h2>
            <a href="{{ route('pelamar.interviews.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
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

                    <!-- Interview Details Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Detail Wawancara</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                                <span class="text-gray-600 font-medium">Tanggal & Waktu:</span>
                                <span class="text-gray-900 font-semibold">
                                    @if($interview->waktu_jadwal)
                                        {{ $interview->waktu_jadwal->format('d M Y H:i') }}
                                    @else
                                        <span class="text-gray-400">Belum ditentukan</span>
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                                <span class="text-gray-600 font-medium">Tipe Wawancara:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ ($interview->type ?? '') === 'Online' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $interview->type ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-200">
                                <p class="text-sm font-semibold text-indigo-700 mb-2">Lokasi / Link:</p>
                                <p class="text-indigo-900 font-semibold break-all">{{ $interview->lokasi ?? 'N/A' }}</p>
                            </div>

                            @if ($interview->catatan)
                                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                    <p class="text-sm font-semibold text-blue-700 mb-2">Catatan dari Perusahaan:</p>
                                    <p class="text-blue-600 whitespace-pre-wrap">{{ $interview->catatan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Company Information -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Informasi Perusahaan</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Nama Perusahaan:</span>
                                <span class="text-gray-900 font-semibold">{{ $interview->lowongan->company->nama_perusahaan }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Email:</span>
                                <span class="text-gray-900 font-semibold">{{ $interview->lowongan->company->user->email }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">No Telp:</span>
                                <span class="text-gray-900 font-semibold">{{ $interview->lowongan->company->no_telp_perusahaan ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-gray-600 font-medium">Alamat:</span>
                                <span class="text-gray-900 font-semibold text-right max-w-xs">{{ $interview->lowongan->company->alamat_perusahaan ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lowongan Information -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Informasi Lowongan</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Judul:</span>
                                <span class="text-gray-900 font-semibold">{{ $interview->lowongan->judul }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Posisi:</span>
                                <span class="text-gray-900 font-semibold">{{ $interview->lowongan->posisi }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Gaji:</span>
                                <span class="text-gray-900 font-semibold">{{ $interview->lowongan->gaji ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sidebar: Status & Actions -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Status Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">Status Wawancara</h3>

                        @php
                            $statusColor = match($interview->status) {
                                'Scheduled' => 'bg-blue-100 text-blue-700 border-blue-300',
                                'Completed' => 'bg-green-100 text-green-700 border-green-300',
                                'Cancelled' => 'bg-red-100 text-red-700 border-red-300',
                                default => 'bg-gray-100 text-gray-700 border-gray-300'
                            };
                            $statusLabel = match($interview->status) {
                                'Scheduled' => 'Dijadwalkan',
                                'Completed' => 'Selesai',
                                'Cancelled' => 'Dibatalkan',
                                default => $interview->status
                            };
                        @endphp

                        <div class="p-4 rounded-xl border-2 {{ $statusColor }} text-center">
                            <p class="font-bold text-lg">{{ $statusLabel }}</p>
                        </div>

                        <!-- Time Countdown (if upcoming) -->
                        @if ($interview->status === 'Scheduled' && $interview->waktu_jadwal && $interview->waktu_jadwal->isFuture())
                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-xl">
                                <p class="text-xs font-semibold text-yellow-700 mb-1">Waktu Tersisa:</p>
                                <p class="text-sm text-yellow-600">
                                    {{ $interview->waktu_jadwal->diffForHumans() }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    @if ($interview->status === 'Scheduled')
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 space-y-3">
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-3">Tindakan</h3>

                            <!-- Mark Attended Button -->
                            <form method="POST" action="{{ route('pelamar.interviews.mark-attended', $interview) }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-md flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Tandai Sudah Hadir
                                </button>
                            </form>

                            <!-- Decline Interview Button -->
                            <button type="button" onclick="document.getElementById('declineModal').showModal()" class="w-full px-4 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition shadow-md flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batalkan Wawancara
                            </button>
                        </div>

                        {{-- Decline Modal --}}
                        <dialog id="declineModal" class="backdrop:bg-black/50 rounded-2xl shadow-2xl max-w-md">
                            <form method="POST" action="{{ route('pelamar.interviews.decline', $interview) }}" class="p-6 space-y-4">
                                @csrf
                                <h2 class="text-xl font-bold text-gray-900">Batalkan Wawancara</h2>

                                <p class="text-gray-600 text-sm">
                                    Apakah Anda yakin ingin membatalkan wawancara ini? Perusahaan akan menerima notifikasi pembatalan.
                                </p>

                                <div class="flex gap-3">
                                    <button type="button" onclick="document.getElementById('declineModal').close()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300">
                                        Tidak, Jangan
                                    </button>
                                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                                        Ya, Batalkan
                                    </button>
                                </div>
                            </form>
                        </dialog>
                    @else
                        <div class="bg-gray-50 rounded-2xl shadow-lg border border-gray-200 p-6 text-center">
                            <p class="text-gray-600 text-sm">
                                @if ($interview->status === 'Completed')
                                    Terima kasih atas kehadiran Anda dalam wawancara!
                                @else
                                    Wawancara ini telah dibatalkan.
                                @endif
                            </p>
                        </div>
                    @endif

                    <!-- Tips Card -->
                    <div class="bg-blue-50 rounded-2xl shadow-lg border border-blue-200 p-6">
                        <h4 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zm3 0a1 1 0 11-2 0 1 1 0 012 0zm3 0a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                            </svg>
                            Tips Persiapan
                        </h4>
                        <ul class="text-sm text-blue-700 space-y-2">
                            <li>• Persiapkan diri 15 menit sebelumnya</li>
                            <li>• Cek koneksi internet (untuk wawancara online)</li>
                            <li>• Siapkan portfolio atau dokumen pendukung</li>
                            <li>• Pilih lokasi yang tenang dan profesional</li>
                        </ul>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
