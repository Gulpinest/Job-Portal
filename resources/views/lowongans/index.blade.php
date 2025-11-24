<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Kelola Lowongan Pekerjaan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg">
                        <p class="font-bold">Berhasil!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                {{-- Alert: Company Not Verified --}}
                @if (Auth::user()->company && !Auth::user()->company->is_verified)
                    <div class="mb-6 p-5 bg-amber-50 border-l-4 border-amber-500 rounded-r-lg shadow-md">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-bold text-amber-800 text-lg">Akun Perusahaan Belum Diverifikasi</p>
                                <p class="text-amber-700 mt-1">Perusahaan Anda belum disetujui oleh administrator. Anda tidak dapat membuat lowongan pekerjaan sampai akun Anda diverifikasi.</p>
                                <p class="text-sm text-amber-600 mt-2">Status: <span class="font-semibold">Menunggu Persetujuan Admin</span></p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tombol Utama --}}
                <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900">Lowongan Aktif ({{ $lowongans->total() }})</h3>
                    @if (Auth::user()->company && Auth::user()->company->is_verified)
                        <a href="{{ route('lowongans.create') }}"
                            class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wider hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Buat Lowongan Baru
                        </a>
                    @else
                        <button disabled
                            class="inline-flex items-center px-6 py-2 bg-gray-400 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wider cursor-not-allowed opacity-75 shadow-md"
                            title="Perusahaan Anda belum diverifikasi">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Buat Lowongan Baru
                        </button>
                    @endif
                </div>

                <div class="space-y-4">
                    @forelse ($lowongans as $lowongan)
                        @php
                            // Pelamar count dari eager loading
                            $totalPelamar = $lowongan->lamarans_count;
                            $pelamarBaru = $lowongan->pelamar_baru_count;

                            // STATUS OPEN/CLOSED (Border Kiri dan Badge Utama)
                            $status = $lowongan->status;
                            $statusColor = $status == 'Open' ? 'border-green-500 hover:shadow-green-100/50' : 'border-red-500 hover:shadow-red-100/50';
                            $statusBadgeClass = $status == 'Open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                            $statusIcon = $status == 'Open' ? 'fas fa-check-circle' : 'fas fa-lock';

                            // TIPE KERJA (Badge sekunder)
                            $tipeKerja = $lowongan->tipe_kerja ?? 'N/A';
                            $tipeBadgeClass = 'bg-gray-100 text-gray-600';
                        @endphp

                        <div
                            class="bg-white border-l-4 {{ $statusColor }} rounded-2xl shadow-lg p-6 flex flex-col md:flex-row justify-between transition duration-300 ease-in-out hover:shadow-xl hover:scale-[1.005]">

                            <!-- Kiri: Detail Lowongan -->
                            <div class="flex-1 md:pr-6 mb-4 md:mb-0">
                                <div class="flex items-center space-x-3 mb-2">
                                    <i class="text-xl text-indigo-600 fas fa-briefcase"></i>
                                    <h3 class="text-xl font-extrabold text-gray-900 leading-tight">{{ $lowongan->judul }}
                                    </h3>
                                </div>
                                <p class="text-sm font-medium text-gray-700">{{ $lowongan->posisi }}</p>

                                <p class="text-xs text-gray-500 mt-1 mb-3">
                                    Diposting: {{ $lowongan->created_at->format('d M Y') }} | Lokasi:
                                    {{ $lowongan->lokasi_kantor ?? 'Remote' }}
                                </p>

                                <!-- Status & Tipe Kerja Badges -->
                                <div class="flex space-x-2 mb-3">
                                    {{-- Status Lowongan (Open/Closed) --}}
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusBadgeClass }}">
                                        <i class="{{ $statusIcon }} mr-1"></i> {{ $status }}
                                    </span>

                                    {{-- Tipe Pekerjaan (Full Time, Remote, dll.) --}}
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $tipeBadgeClass }}">
                                        <i class="fas fa-clock mr-1"></i> {{ $tipeKerja }}
                                    </span>
                                </div>

                                <!-- SKILLS DISPLAY -->
                                <div class="flex flex-wrap gap-2 text-sm">
                                    @forelse ($lowongan->skills as $skill)
                                        <span
                                            class="px-3 py-0.5 text-xs font-medium text-indigo-700 bg-indigo-100 rounded-full">
                                            {{ $skill->nama_skill }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">Tidak ada keterampilan yang
                                            dicantumkan.</span>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Tengah: Metrik Pelamar -->
                            <div
                                class="flex flex-col sm:flex-row md:flex-col space-y-3 sm:space-y-0 sm:space-x-8 md:space-x-0 md:w-48 border-t md:border-t-0 pt-4 md:pt-0 md:pl-6 border-gray-100 md:border-l">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 font-medium">Total Pelamar:</span>
                                    <span class="text-xl font-extrabold text-gray-900">{{ $totalPelamar }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 font-medium">Pelamar Baru:</span>
                                    <span class="text-xl font-extrabold text-red-600">{{ $pelamarBaru }}</span>
                                </div>
                            </div>

                            <!-- Kanan: Tombol Aksi -->
                            <div
                                class="flex flex-col space-y-2 md:w-48 border-t md:border-t-0 pt-4 md:pt-0 md:pl-6 border-gray-100 md:border-l">

                                {{-- Tombol Lihat Pelamar
                                <a href="{{ route('lamaran.show_lowongan', $lowongan->id_lowongan) }}"
                                    class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-md">
                                    <i class="fas fa-users mr-2"></i> Lihat Pelamar ({{ $totalPelamar }})
                                </a> --}}

                                {{-- Tombol Edit --}}
                                <a href="{{ route('lowongans.edit', $lowongan->id_lowongan) }}"
                                    class="inline-flex justify-center items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition shadow-sm">
                                    <i class="fas fa-edit mr-2"></i> Edit Lowongan
                                </a>

                                {{-- Tombol Hapus --}}
                                <form method="POST" action="{{ route('lowongans.destroy', $lowongan->id_lowongan) }}"
                                    onsubmit="return confirm('PERINGATAN: Menghapus lowongan ini akan menghapus semua data pelamar. Apakah Anda yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition shadow-sm">
                                        <i class="fas fa-trash-alt mr-2"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-lg text-center">
                            <p class="text-gray-900 font-semibold text-lg">Anda belum memiliki lowongan pekerjaan yang
                                diposting.</p>
                            <p class="text-sm text-gray-500 mt-2">Mulai rekrutmen dengan membuat lowongan baru.</p>
                            @if (Auth::user()->company && Auth::user()->company->is_verified)
                                <a href="{{ route('lowongans.create') }}"
                                    class="mt-4 inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-700 transition shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Buat Lowongan Pertama Anda
                                </a>
                            @else
                                <button disabled
                                    class="mt-4 inline-flex items-center px-6 py-3 bg-gray-400 border border-transparent rounded-xl font-semibold text-sm text-white cursor-not-allowed opacity-75 shadow-md"
                                    title="Perusahaan Anda belum diverifikasi">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Buat Lowongan Pertama Anda
                                </button>
                            @endif
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($lowongans->hasPages())
                    <div class="mt-8 space-y-4">
                        <div class="text-sm text-gray-600 text-center">
                            Menampilkan <span class="font-semibold">{{ $lowongans->firstItem() }}</span>
                            hingga
                            <span class="font-semibold">{{ $lowongans->lastItem() }}</span>
                            dari
                            <span class="font-semibold">{{ $lowongans->total() }}</span>
                            lowongan
                        </div>
                        <div class="flex justify-center">
                            {{ $lowongans->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
