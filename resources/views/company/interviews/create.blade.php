<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Jadwalkan Wawancara Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('lowongans.show', $lowongan) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Detail Lowongan
                </a>
            </div>

            <!-- Informasi Lowongan -->
            <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Lowongan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Judul Lowongan -->
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Judul Lowongan</p>
                        <p class="text-base font-bold text-gray-900">{{ $lowongan->judul }}</p>
                    </div>
                    <!-- Posisi -->
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Posisi</p>
                        <p class="text-base font-bold text-gray-900">{{ $lowongan->posisi }}</p>
                    </div>
                    <!-- Pelamar Diterima -->
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Pelamar Diterima</p>
                        <p class="text-base font-bold text-green-600">{{ $acceptedApplicants->count() }} orang</p>
                    </div>
                </div>
            </div>

            <!-- Daftar Pelamar yang Akan Mengikuti Wawancara -->
            @if($acceptedApplicants->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pelamar yang Akan Mengikuti Wawancara</h3>
                    <div class="space-y-3">
                        @foreach($acceptedApplicants as $lamaran)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $lamaran->pelamar->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $lamaran->pelamar->user->email }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Diterima</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-6">
                    <p class="text-yellow-800 font-medium">Belum ada pelamar yang diterima untuk lowongan ini.</p>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Isi Detail Wawancara</h3>

                <form method="POST" action="{{ route('interview-schedules.store', $lowongan) }}" class="space-y-6">
                    @csrf

                    <!-- Tanggal & Waktu Wawancara -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal & Waktu Wawancara <span class="text-red-600">*</span></label>
                        <input type="datetime-local" name="waktu_jadwal" required value="{{ old('waktu_jadwal') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">
                        @error('waktu_jadwal')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe Wawancara -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Wawancara <span class="text-red-600">*</span></label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="type" value="Online" @checked(old('type') === 'Online')
                                    class="rounded-full text-indigo-600 focus:ring-indigo-500 mr-3 border-gray-300">
                                <span class="text-gray-700">Online</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="type" value="Offline" @checked(old('type') === 'Offline')
                                    class="rounded-full text-indigo-600 focus:ring-indigo-500 mr-3 border-gray-300">
                                <span class="text-gray-700">Offline</span>
                            </label>
                        </div>
                        @error('type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi / Link Zoom <span class="text-red-600">*</span></label>
                        <textarea name="lokasi" required rows="3" placeholder="Contoh: Ruang Meeting A / https://zoom.us/..." value="{{ old('lokasi') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">{{ old('lokasi') }}</textarea>
                        @error('lokasi')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="4" placeholder="Informasi tambahan untuk pelamar..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-6">
                        <a href="{{ route('interview-schedules.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition text-center">
                            Batal
                        </a>
                        <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition">
                            Jadwalkan Wawancara
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
