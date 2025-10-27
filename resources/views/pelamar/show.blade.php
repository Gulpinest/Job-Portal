<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profil Pelamar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Data Diri</h3>

                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="py-2 grid grid-cols-3">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-gray-200">{{ $pelamar->nama_pelamar ?? '-' }}</dd>
                        </div>
                        <div class="py-2 grid grid-cols-3">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Status Pekerjaan</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-gray-200">{{ $pelamar->status_pekerjaan ?? '-' }}</dd>
                        </div>
                        <div class="py-2 grid grid-cols-3">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">No. Telepon</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-gray-200">{{ $pelamar->no_telp ?? '-' }}</dd>
                        </div>
                        <div class="py-2 grid grid-cols-3">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Alamat</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-gray-200">{{ $pelamar->alamat ?? '-' }}</dd>
                        </div>
                        <div class="py-2 grid grid-cols-3">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Jenis Kelamin</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-gray-200">{{ $pelamar->jenis_kelamin ?? '-' }}</dd>
                        </div>
                        <div class="py-2 grid grid-cols-3">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal Lahir</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-gray-200">{{ $pelamar->tgl_lahir ?? '-' }}</dd>
                        </div>
                    </dl>
                    
                    <div class="mt-6 flex justify-between flex-wrap gap-2">
                        <!-- Tombol Edit Profil -->
                        <a href="{{ route('pelamar.edit') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 focus:outline-none transition ease-in-out duration-150">
                            Edit Profil
                        </a>

                        <!-- Tombol Pengaturan Akun -->
                        <a href="{{ route('profile.edit') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 focus:outline-none transition ease-in-out duration-150">
                            Pengaturan Akun
                        </a>

                        <!-- Tombol Hapus Akun -->
                        <form action="{{ route('pelamar.destroy') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun Anda?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 focus:outline-none transition ease-in-out duration-150">
                                Hapus Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
