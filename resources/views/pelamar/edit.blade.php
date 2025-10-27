<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Profil Pelamar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('pelamar.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <!-- Nama -->
                            <div>
                                <x-input-label for="nama_pelamar" :value="__('Nama Pelamar')" />
                                <x-text-input id="nama_pelamar" name="nama_pelamar" type="text"
                                    class="mt-1 block w-full" value="{{ old('nama_pelamar', $pelamar->nama_pelamar) }}" required autofocus />
                                <x-input-error :messages="$errors->get('nama_pelamar')" class="mt-2" />
                            </div>

                            <!-- Status Pekerjaan -->
                            <div>
                                <x-input-label for="status_pekerjaan" :value="__('Status Pekerjaan')" />
                                <x-text-input id="status_pekerjaan" name="status_pekerjaan" type="text"
                                    class="mt-1 block w-full" value="{{ old('status_pekerjaan', $pelamar->status_pekerjaan) }}" />
                                <x-input-error :messages="$errors->get('status_pekerjaan')" class="mt-2" />
                            </div>

                            <!-- No. Telepon -->
                            <div>
                                <x-input-label for="no_telp" :value="__('No. Telepon')" />
                                <x-text-input id="no_telp" name="no_telp" type="text"
                                    class="mt-1 block w-full" value="{{ old('no_telp', $pelamar->no_telp) }}" />
                                <x-input-error :messages="$errors->get('no_telp')" class="mt-2" />
                            </div>

                            <!-- Alamat -->
                            <div>
                                <x-input-label for="alamat" :value="__('Alamat')" />
                                <textarea id="alamat" name="alamat"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat', $pelamar->alamat) }}</textarea>
                                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                <select id="jenis_kelamin" name="jenis_kelamin"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $pelamar->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $pelamar->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <x-input-label for="tgl_lahir" :value="__('Tanggal Lahir')" />
                                <x-text-input id="tgl_lahir" name="tgl_lahir" type="date"
                                    class="mt-1 block w-full" value="{{ old('tgl_lahir', $pelamar->tgl_lahir) }}" />
                                <x-input-error :messages="$errors->get('tgl_lahir')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('pelamar.profil') }}"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
