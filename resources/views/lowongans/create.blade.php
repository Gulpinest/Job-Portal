<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Lowongan Pekerjaan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('lowongans.store') }}" method="POST">
                        @csrf

                        <!-- Judul -->
                        <div>
                            <label for="judul" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Judul
                                Lowongan</label>
                            <input id="judul" name="judul" type="text"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                value="{{ old('judul') }}" required />
                        </div>

                        <!-- Posisi -->
                        <div class="mt-4">
                            <label for="posisi"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">Posisi</label>
                            <input id="posisi" name="posisi" type="text"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                value="{{ old('posisi') }}" required />
                        </div>

                        <!-- Deskripsi -->
                        <div class="mt-4">
                            <label for="deskripsi"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">Deskripsi
                                Pekerjaan</label>
                            <textarea id="deskripsi" name="deskripsi" rows="5"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('deskripsi') }}</textarea>
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <label for="status"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" id="status"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                <option value="Open">Open</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('lowongans.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Batal</a>
                            <button type="submit"
                                class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest">
                                Simpan Lowongan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>