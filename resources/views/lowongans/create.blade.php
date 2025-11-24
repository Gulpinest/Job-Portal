<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Buat Lowongan Pekerjaan Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">

                <h3 class="text-xl font-extrabold text-gray-900 mb-6 border-b pb-4">
                    Detail Pekerjaan Baru
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

                <form action="{{ route('lowongans.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-6">

                        <!-- BARIS 1: Judul & Posisi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Judul -->
                            <div>
                                <label for="judul" class="block font-medium text-sm text-gray-700">Judul
                                    Lowongan</label>
                                <input id="judul" name="judul" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('judul') }}" required
                                    placeholder="Contoh: Lowongan Digital Marketing Lead" />
                            </div>

                            <!-- Posisi -->
                            <div>
                                <label for="posisi" class="block font-medium text-sm text-gray-700">Posisi</label>
                                <input id="posisi" name="posisi" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('posisi') }}" required placeholder="Contoh: Digital Marketing Lead" />
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
                                    value="{{ old('lokasi_kantor') }}" placeholder="Contoh: Jakarta Selatan, Remote" />
                            </div>

                            <!-- Gaji -->
                            <div>
                                <label for="gaji" class="block font-medium text-sm text-gray-700">Perkiraan Gaji (Per
                                    Bulan)</label>
                                <input id="gaji" name="gaji" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('gaji') }}" placeholder="Contoh: Rp 8.000.000 - Rp 12.000.000" />
                            </div>
                        </div>

                        <!-- BARIS 3: Keterampilan & Tipe Kerja (DIPISAHKAN DARI STATUS) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Keterampilan -->
                            <div>
                                <label for="keterampilan" class="block font-medium text-sm text-gray-700">Keterampilan
                                    Utama (Pisahkan dengan koma)</label>
                                <input id="keterampilan" name="keterampilan" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('keterampilan') }}"
                                    placeholder="Contoh: Laravel, API, Tailwind, MySQL" />
                            </div>

                            <!-- Tipe Kerja (FIELD BARU) -->
                            <div>
                                <label for="tipe_kerja" class="block font-medium text-sm text-gray-700">Tipe
                                    Pekerjaan</label>
                                <select name="tipe_kerja" id="tipe_kerja" required
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">
                                    <option value="">-- Pilih Tipe Kerja --</option>
                                    @php $tipeKerjaOptions = ['Full Time', 'Part Time', 'Remote', 'Freelance', 'Contract']; @endphp
                                    @foreach ($tipeKerjaOptions as $tipe)
                                        <option value="{{ $tipe }}" {{ old('tipe_kerja') == $tipe ? 'selected' : '' }}>
                                            {{ $tipe }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- BARIS 4: STATUS (Status Open/Closed) -->
                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-700">Status Lowongan</label>
                            <select name="status" id="status" required
                                class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">
                                <option value="Open" {{ old('status') == 'Open' ? 'selected' : '' }}>Open (Menerima
                                    Lamaran)</option>
                                <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed (Ditutup)
                                </option>
                            </select>
                        </div>

                        <!-- Skill yang Dibutuhkan -->
                        <div class="mt-4">
                            <label for="skills" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                Skill yang Dibutuhkan
                            </label>
                            <select name="skills[]" id="skills" multiple
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                @foreach ($allSkills as $skill)
                                    <option value="{{ $skill->nama_skill }}"
                                        {{ in_array($skill->nama_skill, $selectedSkills) ? 'selected' : '' }}>
                                        {{ $skill->nama_skill }}
                                    </option>
                                @endforeach
                            </select>


                            <p class="text-xs text-gray-400 mt-2">Gunakan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu skill.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('lowongans.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Batal</a>
                            <button type="submit"
                                class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest">
                                Simpan Lowongan
                            </button>
                        </div>

                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('lowongans.index') }}"
                            class="px-6 py-3 bg-gray-200 text-gray-800 rounded-xl font-semibold hover:bg-gray-300 transition shadow-sm">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <i class="fas fa-plus mr-2"></i> {{ __('Simpan Lowongan') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>