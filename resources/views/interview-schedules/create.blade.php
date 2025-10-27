<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Jadwal Interview Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('interview-schedules.store') }}" method="POST">
                        @csrf

                        <div>
                            <label for="id_lowongan" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                {{ __('Pilih Lowongan') }}
                            </label>
                            <select id="id_lowongan" name="id_lowongan"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option value="">-- Pilih Lowongan --</option>
                                {{-- Ganti dengan data lowongan dari controller Anda --}}
                                @foreach($lowongans as $lowongan)
                                    <option value="{{ $lowongan->id_lowongan }}" {{ old('id_lowongan') == $lowongan->id_lowongan ? 'selected' : '' }}>
                                        {{ $lowongan->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="type" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                {{ __('Tipe Interview') }}
                            </label>
                            <select id="type" name="type"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Online" {{ old('type') == 'Online' ? 'selected' : '' }}>Online</option>
                                <option value="Offline" {{ old('type') == 'Offline' ? 'selected' : '' }}>Offline</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="tempat" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                {{ __('Tempat / Link Interview') }}
                            </label>
                            <input id="tempat" name="tempat" type="text"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                value="{{ old('tempat') }}" placeholder="Contoh: Google Meet atau Alamat Kantor" />
                        </div>

                        <div class="mt-4">
                            <label for="waktu_jadwal"
                                class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                {{ __('Waktu Jadwal') }}
                            </label>
                            <input id="waktu_jadwal" name="waktu_jadwal" type="datetime-local"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                value="{{ old('waktu_jadwal') }}" required />
                        </div>

                        <div class="mt-4">
                            <label for="catatan" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                {{ __('Catatan (Opsional)') }}
                            </label>
                            <textarea id="catatan" name="catatan"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="3">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('interview-schedules.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit"
                                class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Simpan Jadwal') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>