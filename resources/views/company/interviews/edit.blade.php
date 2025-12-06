<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Edit Jadwal Wawancara') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('interview-schedules.show', $interviewSchedule) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Detail Wawancara
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Edit Jadwal Wawancara</h3>

                <form method="POST" action="{{ route('interview-schedules.update', $interviewSchedule) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Lowongan Info (Read-only) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Lowongan</label>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-300">
                            <p class="font-semibold text-gray-900">{{ $interviewSchedule->lowongan->judul }}</p>
                            <p class="text-sm text-gray-600">{{ $interviewSchedule->lowongan->posisi }}</p>
                        </div>
                    </div>

                    <!-- Total Pelamar Diterima (Read-only) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pelamar Diterima</label>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-300">
                            <p class="font-semibold text-gray-900">{{ $interviewSchedule->lamarans->count() }} pelamar</p>
                            <p class="text-sm text-gray-600">Akan mengikuti wawancara ini</p>
                        </div>
                    </div>

                    <!-- Tanggal Wawancara -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Wawancara <span class="text-red-600">*</span></label>
                        <input type="date" name="tanggal_interview" required
                            value="{{ old('tanggal_interview', $interviewSchedule->waktu_jadwal ? $interviewSchedule->waktu_jadwal->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">
                        @error('tanggal_interview')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Wawancara -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jam Wawancara <span class="text-red-600">*</span></label>
                        <input type="time" name="jam_interview" required
                            value="{{ old('jam_interview', $interviewSchedule->waktu_jadwal ? $interviewSchedule->waktu_jadwal->format('H:i') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">
                        @error('jam_interview')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe Wawancara -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Wawancara <span class="text-red-600">*</span></label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="tipe" value="Online" @checked(old('tipe', $interviewSchedule->type) === 'Online')
                                    class="rounded-full text-indigo-600 focus:ring-indigo-500 mr-3 border-gray-300">
                                <span class="text-gray-700">Online</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="tipe" value="Offline" @checked(old('tipe', $interviewSchedule->type) === 'Offline')
                                    class="rounded-full text-indigo-600 focus:ring-indigo-500 mr-3 border-gray-300">
                                <span class="text-gray-700">Offline</span>
                            </label>
                        </div>
                        @error('tipe')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi / Link Zoom <span class="text-red-600">*</span></label>
                        <textarea name="lokasi" required rows="3" placeholder="Contoh: Ruang Meeting A / https://zoom.us/..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">{{ old('lokasi', $interviewSchedule->lokasi) }}</textarea>
                        @error('lokasi')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="4" placeholder="Informasi tambahan untuk pelamar..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">{{ old('catatan', $interviewSchedule->catatan) }}</textarea>
                        @error('catatan')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-6">
                        <a href="{{ route('interview-schedules.show', $interviewSchedule) }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition text-center">
                            Batal
                        </a>
                        <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
