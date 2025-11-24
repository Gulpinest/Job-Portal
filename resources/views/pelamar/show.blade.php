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

            {{-- SECTION SKILLS --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div>
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Skill & Keahlian</h3>
                        <a href="{{ route('skills.create') }}"
                            class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah
                        </a>
                    </div>

                    @if (count($skills) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($skills as $skill)
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $skill->nama_skill }}</h4>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                            @if($skill->level == 'Beginner')
                                                bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($skill->level == 'Intermediate')
                                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($skill->level == 'Advanced')
                                                bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                            @else
                                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @endif
                                        ">
                                            {{ $skill->level }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.754-1 4.5 4.5 0 1-5.385 7.98z"></path>
                                        </svg>
                                        {{ $skill->years_experience ?? 0 }} tahun
                                    </p>
                                    <div class="flex gap-2 pt-2 border-t border-gray-300 dark:border-gray-600">
                                        <a href="{{ route('skills.edit', $skill->id_skill) }}"
                                            class="flex-1 inline-flex justify-center px-2 py-1 bg-yellow-500 text-white text-xs font-medium rounded hover:bg-yellow-600 transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('skills.destroy', $skill->id_skill) }}" method="POST" class="flex-1"
                                            onsubmit="return confirm('Hapus skill ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full inline-flex justify-center px-2 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Belum ada skill yang ditambahkan</p>
                            <a href="{{ route('skills.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Skill Pertama
                            </a>
                        </div>
                    @endif

                    {{-- Link ke halaman skills management --}}
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('skills.index') }}"
                            class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Kelola Semua Skill →
                        </a>
                    </div>
                </div>
            </div>
