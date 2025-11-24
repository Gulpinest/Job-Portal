<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Daftar Skill Saya') }}
            </h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                Total: {{ count($skills) }} skill
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Pesan Sukses --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg font-medium text-sm">
                            ✓ {{ session('success') }}
                        </div>
                    @endif

                    {{-- Tombol Tambah --}}
                    <div class="mb-6">
                        <a href="{{ route('skills.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Skill Baru
                        </a>
                    </div>

                    {{-- Grid Skill Cards --}}
                    @if (count($skills) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($skills as $skill)
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-lg p-5 border border-gray-200 dark:border-gray-600 hover:shadow-md transition">
                                    {{-- Header: Skill Name & Level Badge --}}
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $skill->nama_skill }}
                                        </h3>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
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

                                    {{-- Experience --}}
                                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.754-1 4.5 4.5 0 1-5.385 7.98z"></path>
                                        </svg>
                                        {{ $skill->years_experience ?? 0 }} tahun pengalaman
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex gap-2 pt-3 border-t border-gray-300 dark:border-gray-600">
                                        <a href="{{ route('skills.edit', $skill->id_skill) }}"
                                            class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-yellow-500 text-white text-sm font-medium rounded-md hover:bg-yellow-600 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('skills.destroy', $skill->id_skill) }}" method="POST" class="flex-1"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus skill ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full inline-flex justify-center items-center px-3 py-2 bg-red-500 text-white text-sm font-medium rounded-md hover:bg-red-600 transition">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Belum ada skill</h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">Mulai dengan menambahkan skill untuk profil Anda.</p>
                            <div class="mt-6">
                                <a href="{{ route('skills.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Skill Pertama
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
