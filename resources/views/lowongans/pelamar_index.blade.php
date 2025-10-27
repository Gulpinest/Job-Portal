<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lowongan Pekerjaan Tersedia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                {{-- HILANGKAN: Tombol '+ Buat Lowongan Baru' --}}

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($lowongans as $lowongan)
                        <div
                            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col justify-between">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-bold">{{ $lowongan->judul }}</h3>
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full {{ $lowongan->status == 'Open' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ $lowongan->status }}
                                    </span>
                                </div>

                                <p class="text-sm text-indigo-600 dark:text-indigo-400 mt-1 font-semibold">
                                    {{ $lowongan->company->nama_perusahaan ?? 'Perusahaan Tidak Diketahui' }}
                                </p>

                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $lowongan->posisi }}</p>
                                <p class="text-sm mt-4 line-clamp-3">{{ $lowongan->deskripsi }}</p>
                            </div>
                            <div
                                class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end items-center space-x-3">
                                <a href="{{ route('lowongans.show', $lowongan->id_lowongan) }}"
                                    class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-bold">Lihat Detail
                                    & Lamar</a>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                            <p>Belum ada lowongan pekerjaan yang tersedia saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>