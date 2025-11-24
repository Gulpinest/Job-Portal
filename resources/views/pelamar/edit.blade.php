<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Edit Profil Pelamar') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">

                <h3 class="text-xl font-extrabold text-gray-900 mb-6 border-b pb-4">
                    Perbarui Data Diri
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

                <form method="POST" action="{{ route('pelamar.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                        <!-- Nama -->
                        <div>
                            <label for="nama_pelamar"
                                class="block font-medium text-sm text-gray-700">{{ __('Nama Lengkap') }}</label>
                            <input id="nama_pelamar" name="nama_pelamar" type="text"
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('nama_pelamar', $pelamar->nama_pelamar) }}" required autofocus
                                placeholder="Nama sesuai KTP" />
                        </div>

                        <!-- Status Pekerjaan -->
                        <div>
                            <label for="status_pekerjaan"
                                class="block font-medium text-sm text-gray-700">{{ __('Status Pekerjaan Saat Ini') }}</label>
                            <input id="status_pekerjaan" name="status_pekerjaan" type="text"
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('status_pekerjaan', $pelamar->status_pekerjaan) }}"
                                placeholder="Contoh: Bekerja/Fresh Graduate/Wiraswasta" />
                        </div>

                        <!-- No. Telepon -->
                        <div>
                            <label for="no_telp"
                                class="block font-medium text-sm text-gray-700">{{ __('No. Telepon Aktif') }}</label>
                            <input id="no_telp" name="no_telp" type="text"
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('no_telp', $pelamar->no_telp) }}" placeholder="Contoh: 081234567890" />
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tgl_lahir"
                                class="block font-medium text-sm text-gray-700">{{ __('Tanggal Lahir') }}</label>
                            <input id="tgl_lahir" name="tgl_lahir" type="date"
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('tgl_lahir', $pelamar->tgl_lahir) }}" />
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="md:col-span-2">
                            <label for="jenis_kelamin"
                                class="block font-medium text-sm text-gray-700">{{ __('Jenis Kelamin') }}</label>
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $pelamar->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $pelamar->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="alamat"
                                class="block font-medium text-sm text-gray-700">{{ __('Alamat Lengkap') }}</label>
                            <textarea id="alamat" name="alamat" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Alamat lengkap saat ini">{{ old('alamat', $pelamar->alamat) }}</textarea>
                        </div>

                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('pelamar.profil') }}"
                            class="px-6 py-3 bg-gray-200 text-gray-800 rounded-xl font-semibold hover:bg-gray-300 transition shadow-sm">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-4 0V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m0 0h6">
                                </path>
                            </svg>
                            {{ __('Simpan Perubahan') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>