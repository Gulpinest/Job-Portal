<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Upload Resume Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">

                <h3 class="text-xl font-extrabold text-gray-900 mb-6 border-b pb-4">
                    Informasi Resume
                </h3>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg">
                        <div class="font-bold">{{ __('Whoops! Ada Kesalahan.') }}</div>
                        <ul class="mt-3 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('resumes.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <!-- Judul Resume -->
                    <div>
                        <label for="nama_resume"
                            class="block font-medium text-sm text-gray-700">{{ __('Judul Resume (Contoh: CV Digital Marketing)') }}</label>
                        <input id="nama_resume" name="nama_resume" type="text"
                            class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('nama_resume') }}" required autofocus
                            placeholder="Masukkan nama resume Anda" />
                    </div>

                    <!-- Skill Utama (tetap dipertahankan) -->
                    <div>
                        <label for="skill"
                            class="block font-medium text-sm text-gray-700">{{ __('Skill Utama (Pisahkan dengan koma)') }}</label>
                        <input id="skill" name="skill" type="text"
                            class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('skill') }}" required
                            placeholder="Contoh: Laravel, Figma, Project Management" />
                    </div>

                    <!-- PENDIDIKAN TERAKHIR (BARU DITAMBAHKAN) -->
                    <div>
                        <label for="pendidikan_terakhir"
                            class="block font-medium text-sm text-gray-700">{{ __('Pendidikan Terakhir') }}</label>
                        <select id="pendidikan_terakhir" name="pendidikan_terakhir" required
                            class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Pilih Tingkat Pendidikan --</option>
                            <option value="SMA/SMK" {{ old('pendidikan_terakhir') == 'SMA/SMK' ? 'selected' : '' }}>SMA /
                                SMK</option>
                            <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3 (Diploma)
                            </option>
                            <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1 (Sarjana)
                            </option>
                            <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2 (Magister)
                            </option>
                            <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3 (Doktor)
                            </option>
                            <option value="Lainnya" {{ old('pendidikan_terakhir') == 'Lainnya' ? 'selected' : '' }}>
                                Lainnya</option>
                        </select>
                    </div>

                    <!-- RINGKASAN SINGKAT (BARU DITAMBAHKAN) -->
                    <div>
                        <label for="ringkasan_singkat"
                            class="block font-medium text-sm text-gray-700">{{ __('Ringkasan Singkat (Maks 300 karakter, opsional)') }}</label>
                        <textarea id="ringkasan_singkat" name="ringkasan_singkat" rows="4"
                            class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            maxlength="300"
                            placeholder="Jelaskan secara singkat latar belakang dan tujuan karir Anda.">{{ old('ringkasan_singkat') }}</textarea>
                    </div>

                    <!-- Pilih File -->
                    <div class="mt-4">
                        <label for="file_resume"
                            class="block font-medium text-sm text-gray-700">{{ __('Pilih File Resume (PDF, DOC, DOCX - Maks 2MB)') }}</label>
                        <input id="file_resume" name="file_resume" type="file" accept=".pdf, .doc, .docx"
                            class="block mt-1 w-full text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                            required />
                        <p class="mt-1 text-xs text-gray-500">Hanya format PDF, DOC, atau DOCX yang diterima.</p>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                        <a href="{{ route('resumes.index') }}"
                            class="text-sm font-medium text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                            {{ __('Batal') }}
                        </a>
                        <button type="submit"
                            class="ms-4 inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            {{ __('Upload & Simpan') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>