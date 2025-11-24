<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Daftar Lamaran Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">

                @forelse ($lamarans as $lamaran)
                    @php
                        // Tentukan warna dan ikon berdasarkan status lamaran
                        $status = $lamaran->status ?? 'Menunggu';
                        $colorClass = '';
                        $iconPath = '';

                        switch ($status) {
                            case 'Diterima':
                                $colorClass = 'bg-green-50 border-green-500 text-green-700 hover:border-green-600';
                                $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                break;
                            case 'Ditolak':
                                $colorClass = 'bg-red-50 border-red-500 text-red-700 hover:border-red-600';
                                $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                break;
                            case 'Diproses':
                                $colorClass = 'bg-indigo-50 border-indigo-500 text-indigo-700 hover:border-indigo-600';
                                $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.91 8.91 0 0112 21a9 9 0 01-5.185-1.558L4 17m16-4h-5.582m0 0l-2.458 2.458M12 2l-.001 6"></path>';
                                break;
                            case 'Menunggu':
                            default:
                                $colorClass = 'bg-yellow-50 border-yellow-500 text-yellow-700 hover:border-yellow-600';
                                $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856A2 2 0 0021 17L3 17a2 2 0 012-2z"></path>';
                                break;
                        }
                    @endphp

                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 {{ $colorClass }} transition duration-300 ease-in-out">
                        
                        <div class="flex justify-between items-start">
                            
                            <!-- Detail Pekerjaan -->
                            <div class="flex-1 pr-4">
                                <h3 class="text-xl font-extrabold text-gray-900 leading-tight">
                                    {{ $lamaran->lowongan->judul ?? 'Lowongan Tidak Ditemukan' }}
                                </h3>
                                
                                <p class="text-md font-semibold text-indigo-600 mt-1">
                                    {{ $lamaran->lowongan->company->nama_perusahaan ?? 'Perusahaan Tidak Diketahui' }}
                                </p>
                                
                                <p class="text-sm text-gray-600 mt-1">
                                    Posisi: {{ $lamaran->lowongan->posisi ?? '-' }}
                                </p>
                            </div>

                            <!-- Status & Ikon -->
                            <div class="flex flex-col items-end flex-shrink-0">
                                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-full text-sm font-bold shadow-md {{ $colorClass }}">
                                    
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $iconPath !!}</svg>
                                    <span>{{ $status }}</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Dilamar: {{ $lamaran->created_at->format('d M Y') }}</p>
                            </div>

                        </div>
                        
                        <hr class="my-4 border-gray-100">

                        <div class="flex justify-between items-center flex-wrap gap-3">
                            
                            <!-- Detail Resume & Tanggal Lamaran -->
                            <div class="flex items-center text-sm text-gray-700">
                                <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Resume Digunakan: <span class="font-medium ml-1 text-gray-900">{{ $lamaran->resume->nama_resume ?? 'Resume Dihapus' }}</span>
                            </div>

                            <!-- Tombol Aksi -->
                            <a href="{{ route('lowongans.detail', $lamaran->lowongan->id_lowongan ?? '#') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-100 transition ease-in-out duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Lihat Detail Lowongan
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-lg text-center">
                        <p class="text-gray-900 font-semibold text-lg">Anda belum melamar pekerjaan apa pun saat ini.</p>
                        <a href="{{ route('lowongans.pelamar_index') }}"
                            class="text-indigo-600 hover:text-indigo-700 font-medium mt-3 inline-block">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            Jelajahi Lowongan Tersedia
                        </a>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>