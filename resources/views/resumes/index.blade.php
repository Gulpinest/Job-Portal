<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Daftar Resume Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Tombol Tambah --}}
            <div class="mb-6 flex justify-end">
                <a href="{{ route('resumes.create') }}"
                    class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wider hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Resume Baru
                </a>
            </div>

            {{-- Daftar Resume dalam Bentuk Card --}}
            <div class="space-y-4">
                @forelse ($resumes as $resume)
                    <div
                        class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6 flex flex-col md:flex-row justify-between items-start md:items-center transition duration-300 ease-in-out hover:shadow-xl hover:border-indigo-400">

                        <!-- Detail Resume -->
                        <div class="flex-1 md:pr-6 mb-4 md:mb-0">
                            <div class="flex items-center space-x-4 mb-2">
                                <!-- Ikon File -->
                                <svg class="w-8 h-8 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>

                                <div>
                                    <h3 class="text-xl font-extrabold text-gray-900 leading-tight">
                                        {{ $resume->nama_resume }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Diunggah: {{ $resume->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Ringkasan Singkat (NEW FIELD) --}}
                            @if ($resume->ringkasan_singkat)
                                <p class="text-sm text-gray-700 italic border-l-2 border-indigo-400 pl-3 my-3">
                                    "{{ $resume->ringkasan_singkat }}"
                                </p>
                            @endif

                            <div class="flex flex-wrap items-center gap-4 text-sm mt-3">

                                {{-- Pendidikan Terakhir (NEW FIELD) --}}
                                @if ($resume->pendidikan_terakhir)
                                    <span class="inline-flex items-center text-gray-700 font-medium">
                                        <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l-9 5 9 5 9-5-9-5z"></path>
                                        </svg>
                                        <span class="font-semibold text-gray-800 mr-1">Pendidikan:</span>
                                        {{ $resume->pendidikan_terakhir }}
                                    </span>
                                @endif

                                {{-- Skill Tags --}}
                                <span class="font-semibold text-gray-800">| Skills:</span>
                                @php
                                    // Mengasumsikan $resume->skill adalah string yang dipisahkan koma
                                    $skills = is_string($resume->skill) ? array_map('trim', explode(',', $resume->skill)) : (array) $resume->skill;
                                @endphp

                                <div class="flex flex-wrap gap-2">
                                    @forelse (array_slice($skills, 0, 3) as $skill) {{-- Tampilkan 3 skill teratas --}}
                                        @if (!empty($skill))
                                            <span class="px-3 py-1 bg-gray-100 text-indigo-600 text-xs rounded-full font-medium">
                                                {{ $skill }}
                                            </span>
                                        @endif
                                    @empty
                                        <span class="text-sm text-gray-400">N/A</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Aksi (Edit, Lihat, Hapus) -->
                        <div
                            class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 text-sm flex-shrink-0 w-full md:w-auto">

                            {{-- Tombol Lihat File --}}
                            <a href="{{ Storage::url($resume->file_resume) }}" target="_blank"
                                class="inline-flex justify-center items-center px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-xl font-semibold text-indigo-700 hover:bg-indigo-100 transition duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Lihat File
                            </a>

                            {{-- Tombol Edit --}}
                            <a href="{{ route('resumes.edit', $resume->id_resume) }}"
                                class="inline-flex justify-center items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-xl font-semibold text-white hover:bg-yellow-600 transition duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit
                            </a>

                            {{-- Form Hapus --}}
                            <form method="POST" action="{{ route('resumes.destroy', $resume->id_resume) }}"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus resume ini? Aksi ini tidak dapat dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex justify-center items-center w-full sm:w-auto px-4 py-2 bg-red-600 border border-transparent rounded-xl font-semibold text-white hover:bg-red-700 transition duration-150 shadow-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 bg-white border border-gray-200 rounded-xl shadow-lg text-center">
                        <p class="text-gray-900 font-semibold">Anda belum memiliki resume yang tersimpan.</p>
                        <p class="text-sm text-gray-500 mt-2">Klik tombol "+ Tambah Resume Baru" di atas untuk memulai.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>