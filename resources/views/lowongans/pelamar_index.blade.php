<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lowongan Pekerjaan Tersedia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                {{-- FORM PENCARIAN & FILTER STATUS --}}
                <form method="GET" action="{{ route('lowongans.pelamar_index') }}" class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-3 md:space-y-0">
                        {{-- Search Input --}}
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul atau Posisi..."
                            class="w-full md:w-2/3 px-4 py-2 rounded-lg border border-gray-300 focus:ring focus:ring-indigo-200 text-gray-800">

                        {{-- Filter Status --}}
                        <select name="status" class="w-full md:w-1/3 px-4 py-2 rounded-lg border border-gray-300 text-gray-800">
                            <option value="">Semua Status</option>
                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>

                             {{-- Checkbox Skill Match --}}
                        <div class="flex items-center gap-2 text-sm">
                            <input type="checkbox" 
                                name="match" 
                                value="true" 
                                id="match" 
                                {{ request('match') === 'true' ? 'checked' : '' }}
                                class="w-4 h-4 accent-indigo-500">
                            <label for="match" class="text-gray-300">Tampilkan yang sesuai skill saya</label>
                        </div>
                        {{-- Tombol Submit --}}
                        <button type="submit"
                            class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Cari
                        </button>
                    </div>
                </form>

                {{-- LIST LOWONGAN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($lowongans as $lowongan)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col justify-between">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-bold">{{ $lowongan->judul }}</h3>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $lowongan->status == 'Open' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ $lowongan->status }}
                                    </span>
                                </div>

                                <p class="text-sm text-indigo-600 dark:text-indigo-400 mt-1 font-semibold">
                                    {{ $lowongan->company->nama_perusahaan ?? 'Perusahaan Tidak Diketahui' }}
                                </p>

                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $lowongan->posisi }}</p>
                                <p class="text-sm mt-4 line-clamp-3">{{ $lowongan->deskripsi }}</p>
                            </div>
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end items-center space-x-3">
                                <a href="{{ route('lowongans.detail', $lowongan->id_lowongan) }}"
                                    class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-bold">Lihat Detail & Lamar</a>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                            <p>Tidak ada lowongan yang sesuai dengan pencarian atau filter.</p>
                        </div>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                <div class="mt-6">
                    {{ $lowongans->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
