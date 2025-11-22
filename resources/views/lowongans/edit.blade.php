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
                            <!-- Lokasi Kantor/Remote (BARU DITAMBAHKAN) -->
                            <div>
                                <label for="lokasi_kantor" class="block font-medium text-sm text-gray-700">Lokasi
                                    Kantor/Remote</label>
                                <input id="lokasi_kantor" name="lokasi_kantor" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('lokasi_kantor', $lowongan->lokasi_kantor ?? '') }}"
                                    placeholder="Contoh: Jakarta Selatan, Remote" />
                            </div>

                            <!-- Gaji (BARU DITAMBAHKAN) -->
                            <div>
                                <label for="gaji" class="block font-medium text-sm text-gray-700">Perkiraan Gaji (Per
                                    Bulan)</label>
                                <input id="gaji" name="gaji" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('gaji', $lowongan->gaji ?? '') }}"
                                    placeholder="Contoh: Rp 8.000.000 - Rp 12.000.000" />
                            </div>
                        </div>

                        <!-- BARIS 3: Keterampilan & Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Keterampilan (BARU DITAMBAHKAN) -->
                            <div>
                                <label for="keterampilan" class="block font-medium text-sm text-gray-700">Keterampilan
                                    Utama (Pisahkan dengan koma)</label>
                                <input id="keterampilan" name="keterampilan" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('keterampilan', $lowongan->keterampilan ?? '') }}"
                                    placeholder="Contoh: Laravel, API, Tailwind, MySQL" />
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700">Status
                                    Lowongan</label>
                                <select name="status" id="status"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">
                                    <option value="Open" {{ old('status', $lowongan->status) == 'Open' ? 'selected' : '' }}>Open (Menerima Lamaran)</option>
                                    <option value="Closed" {{ old('status', $lowongan->status) == 'Closed' ? 'selected' : '' }}>Closed (Ditutup)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Deskripsi (Full Width) -->
                        <div>
                            <label for="deskripsi" class="block font-medium text-sm text-gray-700">Deskripsi Pekerjaan
                                (Gunakan format paragraf atau list)</label>
                            <textarea id="deskripsi" name="deskripsi" rows="8"
                                class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Jelaskan tanggung jawab utama, persyaratan, dan manfaat pekerjaan.">{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
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