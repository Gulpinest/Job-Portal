<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Detail Lamaran') }}
            </h2>
            <a href="{{ route('company.lamarans.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                ← Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Success/Error Messages --}}
            @if (session('success') || session('error'))
                <div class="mb-6 p-4 text-sm rounded-2xl {{ session('success') ? 'bg-green-100 text-green-800 border border-green-400' : 'bg-red-100 text-red-800 border border-red-400' }} shadow-md">
                    {{ session('success') ?? session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Pelamar Information --}}
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Informasi Pelamar</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Nama:</span>
                                <span class="text-gray-900 font-semibold">{{ $lamaran->pelamar->user->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Email:</span>
                                <span class="text-gray-900 font-semibold">{{ $lamaran->pelamar->user->email }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">No Telp:</span>
                                <span class="text-gray-900 font-semibold">{{ $lamaran->pelamar->no_telp ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Alamat:</span>
                                <span class="text-gray-900 font-semibold">{{ $lamaran->pelamar->alamat ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Lowongan Information --}}
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Lowongan</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Judul:</span>
                                <span class="text-gray-900 font-semibold">{{ $lamaran->lowongan->judul }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Posisi:</span>
                                <span class="text-gray-900 font-semibold">{{ $lamaran->lowongan->posisi }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Gaji:</span>
                                <span class="text-gray-900 font-semibold">{{ $lamaran->lowongan->gaji ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Resume Information --}}
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Resume Pelamar</h3>

                        @if ($lamaran->resume)
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-medium">Nama Resume:</span>
                                    <span class="text-gray-900 font-semibold">{{ $lamaran->resume->nama_resume }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-medium">Pendidikan:</span>
                                    <span class="text-gray-900 font-semibold">{{ $lamaran->resume->pendidikan_terakhir ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-gray-600 font-medium">Ringkasan:</span>
                                    <span class="text-gray-900 font-semibold text-right max-w-xs">{{ $lamaran->resume->ringkasan_singkat ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-gray-600 font-medium">Skills:</span>
                                    <span class="text-gray-900 font-semibold text-right max-w-xs">{{ $lamaran->resume->skill ?? '-' }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-600 italic">Resume tidak tersedia</p>
                        @endif
                    </div>

                </div>

                {{-- Sidebar: Actions --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Status Card --}}
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">Status Lamaran</h3>

                        @php
                            $statusColor = match($lamaran->status_ajuan) {
                                'Pending' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                                'Accepted' => 'bg-green-100 text-green-700 border-green-300',
                                'Rejected' => 'bg-red-100 text-red-700 border-red-300',
                                default => 'bg-gray-100 text-gray-700 border-gray-300'
                            };
                            $statusText = match($lamaran->status_ajuan) {
                                'Pending' => 'Menunggu',
                                'Accepted' => 'Diterima',
                                'Rejected' => 'Ditolak',
                                default => $lamaran->status_ajuan
                            };
                        @endphp

                        <div class="p-4 rounded-xl border-2 {{ $statusColor }} text-center">
                            <p class="font-bold text-lg">{{ $statusText }}</p>
                            <p class="text-sm mt-1 opacity-75">{{ $lamaran->created_at->format('d M Y H:i') }}</p>
                        </div>

                        @if ($lamaran->status_ajuan === 'Rejected' && $lamaran->rejection_reason)
                            <div class="mt-4 p-4 bg-red-50 rounded-xl border border-red-200">
                                <p class="text-sm font-semibold text-red-700 mb-1">Alasan Penolakan:</p>
                                <p class="text-sm text-red-600">{{ $lamaran->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    @if ($lamaran->status_ajuan === 'Pending')
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 space-y-3">
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-3">Tindakan</h3>

                            {{-- Accept Button --}}
                            <form method="POST" action="{{ route('company.lamarans.accept', $lamaran->id_lamaran) }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-md flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Terima Lamaran
                                </button>
                            </form>

                            {{-- Reject Button & Modal --}}
                            <button type="button" class="w-full px-4 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition shadow-md flex items-center justify-center" onclick="document.getElementById('rejectModal').showModal()">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Tolak Lamaran
                            </button>
                        </div>

                        {{-- Reject Modal --}}
                        <dialog id="rejectModal" class="backdrop:bg-black/50 rounded-2xl shadow-2xl max-w-md">
                            <form method="POST" action="{{ route('company.lamarans.reject', $lamaran->id_lamaran) }}" class="p-6 space-y-4">
                                @csrf
                                <h2 class="text-xl font-bold text-gray-900">Tolak Lamaran</h2>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan (Opsional)</label>
                                    <textarea name="alasan_penolakan" rows="4" placeholder="Jelaskan alasan penolakan..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 text-gray-900"
                                        maxlength="500"></textarea>
                                </div>

                                <div class="flex gap-3">
                                    <button type="button" onclick="document.getElementById('rejectModal').close()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300">
                                        Batal
                                    </button>
                                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                                        Tolak
                                    </button>
                                </div>
                            </form>
                        </dialog>
                    @elseif ($lamaran->status_ajuan === 'Accepted')
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">Jadwal Wawancara</h3>

                            @php
                                $interviewSchedule = $lamaran->interviewSchedule;
                                $lowonganSchedules = $lamaran->lowongan->interviewSchedules;
                            @endphp

                            @if($interviewSchedule)
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                                    <p class="text-blue-700 font-semibold text-sm">✓ Wawancara untuk lamaran ini sudah dijadwalkan</p>
                                    <p class="text-blue-600 text-xs mt-2"><strong>Tanggal:</strong> {{ $interviewSchedule->waktu_jadwal->format('d M Y H:i') }}</p>
                                    <p class="text-blue-600 text-xs"><strong>Lokasi:</strong> {{ $interviewSchedule->lokasi }}</p>
                                    <p class="text-blue-600 text-xs"><strong>Tipe:</strong> {{ $interviewSchedule->type }}</p>
                                </div>
                            @endif

                            @if($lowonganSchedules->isNotEmpty())
                                <div class="mb-4">
                                    <p class="text-sm font-semibold text-gray-700 mb-2">Jadwal Lowongan yang Tersedia:</p>
                                    <div class="space-y-2">
                                        @foreach($lowonganSchedules as $schedule)
                                            <div class="bg-gray-50 border border-gray-300 rounded-lg p-3 text-sm">
                                                <p class="text-gray-700"><strong>{{ $schedule->waktu_jadwal->format('d M Y H:i') }}</strong></p>
                                                <p class="text-gray-600">📍 {{ $schedule->lokasi }}</p>
                                                <p class="text-gray-600">{{ ucfirst($schedule->type) }}</p>
                                                <p class="text-gray-500 text-xs mt-1">Status: <span class="font-semibold">{{ $schedule->status }}</span></p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(!$interviewSchedule)
                                <a href="{{ route('interview-schedules.create', $lamaran->lowongan) }}" class="inline-block w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition text-sm text-center">
                                    + Jadwalkan Wawancara untuk Pelamar Ini
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-2xl shadow-lg border border-gray-200 p-6 text-center">
                            <p class="text-gray-600 text-sm">
                                Lamaran telah ditolak
                            </p>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
