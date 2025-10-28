<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lamaran Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @forelse ($lamarans as $lamaran)
                                    <div class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md">

                                        {{-- Judul Lowongan --}}
                                        <h3 class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ $lamaran->lowongan->judul ?? 'Lowongan Tidak Ditemukan' }}
                                        </h3>

                                        {{-- Detail Perusahaan dan Posisi --}}
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Posisi: {{ $lamaran->lowongan->posisi ?? '-' }}
                                        </p>
                                        <p class="text-md font-semibold text-gray-700 dark:text-gray-300">
                                            Perusahaan:
                                            {{ $lamaran->lowongan->company->nama_perusahaan ?? 'Perusahaan Tidak Diketahui' }}
                                        </p>

                                        <hr class="my-3 border-gray-100 dark:border-gray-700">

                                        <div class="flex justify-between items-center mt-2">
                                            {{-- Status Lamaran --}}
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Status Lamaran:</p>
                                                <span
                                                    class="px-3 py-1 text-sm font-semibold rounded-full 
                                                                                                        {{ $lamaran->status == 'Diterima' ? 'bg-green-200 text-green-800' :
                        ($lamaran->status == 'Ditolak' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                                    {{ $lamaran->status }}
                                                </span>
                                            </div>

                                            {{-- Resume yang Digunakan --}}
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Resume Digunakan:</p>
                                                <p class="font-medium text-gray-700 dark:text-gray-300">
                                                    {{ $lamaran->resume->nama_resume ?? 'Resume Dihapus' }}
                                                </p>
                                            </div>
                                        </div>
                                        <p class="text-xs text-right text-gray-400 dark:text-gray-500 mt-2">
                                            Dilamar pada: {{ $lamaran->created_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-lg">Anda belum melamar pekerjaan apa pun saat ini.</p>
                            <a href="{{ route('lowongans.pelamar_index') }}"
                                class="text-indigo-600 dark:text-indigo-400 hover:underline mt-4 block">
                                Jelajahi Lowongan Tersedia
                            </a>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>