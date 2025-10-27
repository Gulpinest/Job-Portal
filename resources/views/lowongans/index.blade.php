<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Lowongan Pekerjaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                @if (session('success'))
                    <div
                        class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900 border border-green-400 rounded-lg p-4">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('lowongans.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mb-6">
                    + Buat Lowongan Baru
                </a>

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
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $lowongan->posisi }}</p>
                                <p class="text-sm mt-4 line-clamp-3">{{ $lowongan->deskripsi }}</p>
                            </div>
                            <div
                                class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end items-center space-x-3">
                                <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                    action="{{ route('lowongans.destroy', $lowongan->id_lowongan) }}" method="POST">
                                    <a href="{{ route('lowongans.edit', $lowongan->id_lowongan) }}"
                                        class="text-sm text-yellow-600 dark:text-yellow-400 hover:underline">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-sm text-red-600 dark:text-red-400 hover:underline ml-3">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                            <p>Anda belum memiliki lowongan pekerjaan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>