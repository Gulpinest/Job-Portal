<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Edit Lowongan Pekerjaan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">

                <h3 class="text-xl font-extrabold text-gray-900 mb-6 border-b pb-4">
                    Perbarui Detail Lowongan
                </h3>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg">
                        <div class="font-bold">{{ __('Whoops! Ada Kesalahan.') }}</div>
                        <ul class="mt-3 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('lowongans.update', $lowongan->id_lowongan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">

                        <!-- BARIS 1: Judul & Posisi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Judul -->
                            <div>
                                <label for="judul" class="block font-medium text-sm text-gray-700">Judul
                                    Lowongan</label>
                                <input id="judul" name="judul" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('judul', $lowongan->judul) }}" required
                                    placeholder="Contoh: Lowongan Digital Marketing Lead" />
                            </div>

                            <!-- Posisi -->
                            <div>
                                <label for="posisi" class="block font-medium text-sm text-gray-700">Posisi</label>
                                <input id="posisi" name="posisi" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('posisi', $lowongan->posisi) }}" required
                                    placeholder="Contoh: Digital Marketing Lead" />
                            </div>
                        </div>

                        <!-- BARIS 2: Lokasi & Gaji -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Lokasi Kantor/Remote -->
                            <div>
                                <label for="lokasi_kantor" class="block font-medium text-sm text-gray-700">Lokasi
                                    Kantor/Remote</label>
                                <input id="lokasi_kantor" name="lokasi_kantor" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('lokasi_kantor', $lowongan->lokasi_kantor ?? '') }}"
                                    placeholder="Contoh: Jakarta Selatan, Remote" />
                            </div>

                            <!-- Gaji -->
                            <div>
                                <label for="gaji" class="block font-medium text-sm text-gray-700">Perkiraan Gaji (Per
                                    Bulan)</label>
                                <input id="gaji" name="gaji" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('gaji', $lowongan->gaji ?? '') }}"
                                    placeholder="Contoh: Rp 8.000.000 - Rp 12.000.000" />
                            </div>
                        </div>

                        <!-- BARIS 3: Tipe Kerja -->
                        <div>
                            <label for="tipe_kerja" class="block font-medium text-sm text-gray-700">Tipe
                                Pekerjaan</label>
                            <select name="tipe_kerja" id="tipe_kerja" required
                                class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">
                                <option value="">-- Pilih Tipe Kerja --</option>
                                @php
                                    $tipeKerjaOptions = ['Full Time', 'Part Time', 'Remote', 'Freelance', 'Contract'];
                                    $currentTipe = old('tipe_kerja', $lowongan->tipe_kerja);
                                @endphp
                                @foreach ($tipeKerjaOptions as $tipe)
                                    <option value="{{ $tipe }}" {{ $currentTipe == $tipe ? 'selected' : '' }}>{{ $tipe }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- BARIS 4: STATUS (Full Width) -->
                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-700">Status Lowongan</label>
                            <select name="status" id="status" required
                                class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">
                                <option value="Open" {{ old('status', $lowongan->status) == 'Open' ? 'selected' : '' }}>
                                    Open (Menerima Lamaran)</option>
                                <option value="Closed" {{ old('status', $lowongan->status) == 'Closed' ? 'selected' : '' }}>Closed (Ditutup)</option>
                            </select>
                        </div>

                        <!-- Skill yang Dibutuhkan -->
                        <div class="mt-6 p-6 bg-gray-50 rounded-2xl border border-gray-200">
                            <label class="block font-bold text-sm text-gray-900 mb-4">
                                <svg class="w-4 h-4 inline mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.488 5.951 1.488a1 1 0 001.169-1.409l-7-14z"></path>
                                </svg>
                                Skill yang Dibutuhkan
                            </label>
                            
                            @if($allSkills->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach ($allSkills as $skill)
                                        <label class="flex items-center p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-indigo-50 transition">
                                            <input type="checkbox" name="skills[]" value="{{ $skill->nama_skill }}"
                                                {{ in_array($skill->nama_skill, $selectedSkills) ? 'checked' : '' }}
                                                class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                                            <span class="ml-3 text-sm text-gray-700">{{ $skill->nama_skill }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500 mt-3">Pilih satu atau lebih skill yang diperlukan untuk posisi ini.</p>
                            @else
                                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                    <p class="text-sm text-amber-800">Belum ada skill master. <a href="{{ route('admin.skills.index') }}" class="font-semibold hover:underline">Buat skill master terlebih dahulu</a></p>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('lowongans.index') }}"
                                class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">Batal</a>
                            <button type="submit"
                                class="ms-4 inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-700 transition shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Lowongan
                            </button>
                        </div>

                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('lowongans.index') }}"
                            class="px-6 py-3 bg-gray-200 text-gray-800 rounded-xl font-semibold hover:bg-gray-300 transition shadow-sm">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <i class="fas fa-save mr-2"></i> {{ __('Simpan Perubahan') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>