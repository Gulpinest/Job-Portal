<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LEFT COLUMN: Informasi Detail Pelamar -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- CARD 1: INFORMASI DATA DIRI -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                        <h3 class="text-xl font-extrabold text-gray-900 mb-6 border-b pb-4">
                            Data Pribadi & Kontak
                        </h3>

                        <!-- Header Foto dan Nama -->
                        <div class="flex items-center mb-6">
                            <!-- Placeholder Foto Profil -->
                            <div
                                class="w-16 h-16 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-2xl mr-4">
                                {{ strtoupper(substr($pelamar->nama_pelamar ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-2xl font-extrabold text-gray-900">
                                    {{ $pelamar->nama_pelamar ?? 'Pelamar Baru' }}</p>
                                <p class="text-sm text-gray-500">{{ Auth::user()->email ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Detail Informasi -->
                        <dl class="divide-y divide-gray-100">
                            {{-- Field NAMA (sudah ada di header, dihilangkan dari list) --}}

                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-600 flex items-center"><svg
                                        class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v12m-4-8h8m-4 8v-8m-4-8H4a2 2 0 00-2 2v10a2 2 0 002 2h4">
                                        </path>
                                    </svg> Status Pekerjaan</dt>
                                <dd class="col-span-2 text-sm text-gray-900 font-medium">
                                    {{ $pelamar->status_pekerjaan ?? 'Belum Diisi' }}</dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-600 flex items-center"><svg
                                        class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-4.475a1 1 0 01-1-.75L12 14.75a1 1 0 00-.75-1L6.75 12.75a1 1 0 00-1 .75L4.275 19a1 1 0 01-1 .75H3a2 2 0 01-2-2z">
                                        </path>
                                    </svg> No. Telepon</dt>
                                <dd class="col-span-2 text-sm text-gray-900 font-medium">{{ $pelamar->no_telp ?? '-' }}
                                </dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-600 flex items-center"><svg
                                        class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg> Alamat</dt>
                                <dd class="col-span-2 text-sm text-gray-900 font-medium">{{ $pelamar->alamat ?? '-' }}
                                </dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-600 flex items-center"><svg
                                        class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h-4v-2h4v2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 5h3a2 2 0 012 2v3m-3 0h-3v2h6m-3-2V7a2 2 0 00-2-2h-4a2 2 0 00-2 2v6m0 0H4a2 2 0 00-2 2v3a2 2 0 002 2h10m-10-6h14m-7-2v6">
                                        </path>
                                    </svg> Jenis Kelamin</dt>
                                <dd class="col-span-2 text-sm text-gray-900 font-medium">
                                    {{ $pelamar->jenis_kelamin ?? '-' }}</dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-600 flex items-center"><svg
                                        class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg> Tanggal Lahir</dt>
                                <dd class="col-span-2 text-sm text-gray-900 font-medium">
                                    {{ $pelamar->tgl_lahir ? \Carbon\Carbon::parse($pelamar->tgl_lahir)->format('d F Y') : '-' }}
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-8">
                            <a href="{{ route('pelamar.edit') }}"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit Data Diri
                            </a>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Pengaturan Akun & Resume Summary -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- CARD 2: SUMMARY RESUME -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-extrabold text-gray-900 mb-4 border-b pb-3">
                            Ringkasan Resume
                        </h3>

                        <!-- Resume Count -->
                        <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg mb-4">
                            <span class="text-sm font-semibold text-indigo-700">Total Resume Tersimpan:</span>
                            {{-- ASUMSI: $pelamar memiliki relasi ke resumes atau bisa dihitung --}}
                            <span
                                class="text-xl font-extrabold text-indigo-900">{{ $pelamar->resumes_count ?? 0 }}</span>
                        </div>

                        <p class="text-sm text-gray-600 mb-4">Kelola semua CV dan resume Anda untuk mempermudah proses
                            melamar.</p>

                        <a href="{{ route('resumes.index') }}"
                            class="inline-flex items-center justify-center w-full px-4 py-2 bg-indigo-500 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-600 focus:outline-none transition ease-in-out duration-150 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Lihat Semua Resume
                        </a>
                    </div>

                    <!-- CARD 3: PENGATURAN AKUN -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-extrabold text-gray-900 mb-4 border-b pb-3">
                            Pengaturan Akun
                        </h3>

                        <div class="space-y-3">

                            <!-- Tombol Pengaturan Akun -->
                            <a href="{{ route('profile.edit') }}"
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl font-semibold text-sm text-gray-800 hover:bg-gray-200 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ubah Password & Email
                            </a>

                            <!-- Tombol Hapus Akun -->
                            <form action="{{ route('pelamar.destroy') }}" method="POST"
                                onsubmit="return confirm('PERINGATAN: Menghapus akun bersifat permanen. Apakah Anda yakin ingin menghapus akun Anda?')"
                                class="pt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none transition ease-in-out duration-150 shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Hapus Akun Permanen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>