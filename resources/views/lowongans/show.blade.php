<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $lowongan->judul }} - {{ $lowongan->company->nama_perusahaan ?? 'Perusahaan' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Bagian Pemberitahuan Sukses/Gagal Lamaran --}}
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Detail Lowongan --}}
                <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">{{ $lowongan->posisi }}</h3>
                <p class="text-lg text-indigo-600 dark:text-indigo-400 mb-4">
                    {{ $lowongan->company->nama_perusahaan ?? 'Perusahaan Tidak Diketahui' }}</p>

                <div class="mb-6">
                    <p class="font-semibold text-gray-700 dark:text-gray-300">Status:</p>
                    <span
                        class="px-3 py-1 text-sm font-semibold rounded-full {{ $lowongan->status == 'Open' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                        {{ $lowongan->status }}
                    </span>
                </div>

                <div class="prose dark:prose-invert max-w-none">
                    <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi Pekerjaan:</p>
                    <p>{{ $lowongan->deskripsi }}</p>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                {{-- Formulir Kirim Lamaran --}}
                @if ($lowongan->status == 'Open')
                    <h4 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Kirim Lamaran</h4>

                    @if ($resumes->isEmpty())
                        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
                            role="alert">
                            Anda harus memiliki <a href="{{ route('resumes.create') }}" class="font-bold underline">Resume
                                (CV)</a> sebelum bisa melamar.
                        </div>
                    @else
                        <form method="POST" action="{{ route('lamaran.store') }}">
                            @csrf

                            {{-- ID Lowongan disembunyikan --}}
                            <input type="hidden" name="id_lowongan" value="{{ $lowongan->id_lowongan }}">

                            <div class="mb-4">
                                <label for="id_resume" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Pilih
                                    Resume/CV:</label>
                                <select name="id_resume" id="id_resume" required
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="" disabled selected>-- Pilih Resume Anda --</option>
                                    @foreach ($resumes as $resume)
                                        {{-- Sesuaikan 'nama_file' dengan field yang menampilkan nama resume Anda --}}
                                        <option value="{{ $resume->id_resume }}">{{ $resume->nama_file }}
                                            ({{ $resume->created_at->format('d M Y') }})</option>
                                    @endforeach
                                </select>
                                @error('id_resume')
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Kirim Lamaran
                            </button>
                        </form>
                    @endif
                @else
                    <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                        role="alert">
                        Lowongan ini sudah ditutup dan tidak menerima lamaran.
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>