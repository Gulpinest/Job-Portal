<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- 1. HEADER & STATUS -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">Halo, {{ Auth::user()->name }}!</h3>
                <p class="text-md text-gray-500">Selamat datang kembali. Mari lihat status karir Anda hari ini.</p>
                
                {{-- Placeholder status profile --}}
                <div class="mt-4 p-3 bg-indigo-50 border border-indigo-200 rounded-xl flex justify-between items-center">
                    <span class="text-sm font-medium text-indigo-700">
                        Pastikan profil Anda lengkap! (Status Resume: 75% Lengkap)
                    </span>
                    <a href="{{ route('pelamar.profil') }}" class="text-sm font-semibold text-indigo-800 hover:text-indigo-900 underline">
                        Lengkapi Sekarang
                    </a>
                </div>
            </div>

            <!-- 2. APPLICATION SUMMARY CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                {{-- Card 1: Total Lamaran --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Total Lamaran</p>
                    <p class="text-3xl font-extrabold text-indigo-600 mt-1">{{ $lamaranCount['total'] ?? 12 }}</p>
                </div>
                
                {{-- Card 2: Menunggu Review --}}
                <div class="bg-yellow-50 rounded-2xl p-6 shadow-md border border-yellow-200">
                    <p class="text-sm font-medium text-yellow-700">Menunggu Review</p>
                    <p class="text-3xl font-extrabold text-yellow-800 mt-1">{{ $lamaranCount['menunggu'] ?? 5 }}</p>
                </div>
                
                {{-- Card 3: Diproses (Shortlisted) --}}
                <div class="bg-indigo-50 rounded-2xl p-6 shadow-md border border-indigo-200">
                    <p class="text-sm font-medium text-indigo-700">Diproses</p>
                    <p class="text-3xl font-extrabold text-indigo-800 mt-1">{{ $lamaranCount['diproses'] ?? 3 }}</p>
                </div>
                
                {{-- Card 4: Diterima --}}
                <div class="bg-green-50 rounded-2xl p-6 shadow-md border border-green-200">
                    <p class="text-sm font-medium text-green-700">Diterima / Wawancara</p>
                    <p class="text-3xl font-extrabold text-green-800 mt-1">{{ $lamaranCount['diterima'] ?? 1 }}</p>
                </div>
            </div>

            <!-- 3. MAIN CONTENT: Quick Actions & Recommended Jobs -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Left Column: Quick Actions & Latest Activity --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Quick Action Buttons -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 space-y-3">
                        <h4 class="text-lg font-bold text-gray-900 border-b pb-3">Aksi Cepat</h4>
                        <a href="{{ route('lowongans.pelamar_index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-700 transition shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari Lowongan Baru
                        </a>
                        <a href="{{ route('lowongans.lamaran_saya') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 hover:bg-gray-200 transition shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Cek Status Lamaran
                        </a>
                    </div>

                    <!-- Latest Activity / Timeline (Placeholder) -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 space-y-4">
                        <h4 class="text-lg font-bold text-gray-900 border-b pb-3">Aktivitas Terbaru</h4>
                        
                        <div class="space-y-3 text-sm">
                            <p class="text-gray-700 border-l-2 border-indigo-500 pl-3">
                                **Status Lamaran** untuk Posisi **Digital Marketer** diubah menjadi **Diproses**. <span class="text-xs text-gray-400 block">1 jam yang lalu</span>
                            </p>
                            <p class="text-gray-700 border-l-2 border-gray-300 pl-3">
                                Anda telah melamar posisi **UI/UX Designer** di PT Creative Agency. <span class="text-xs text-gray-400 block">5 jam yang lalu</span>
                            </p>
                            <p class="text-gray-700 border-l-2 border-green-500 pl-3">
                                **Jadwal Interview** untuk Posisi **Front End Lead** dikirimkan. <span class="text-xs text-gray-400 block">Kemarin</span>
                            </p>
                        </div>
                        <a href="#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 mt-3 block">
                            Lihat Semua Notifikasi
                        </a>
                    </div>
                </div>

                {{-- Right Column: Recommended Jobs --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">Lowongan Rekomendasi untuk Anda</h4>
                        
                        <div class="space-y-4">
                            {{-- Contoh Card Lowongan (3-4 item) --}}
                            @for ($i = 0; $i < 4; $i++)
                                <a href="{{ route('lowongans.detail', $i) }}"
                                    class="block p-4 border border-gray-100 rounded-xl flex items-center justify-between hover:bg-gray-50 transition duration-150">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-sm font-bold text-gray-700">
                                            PT{{ $i+1 }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">Posisi Rekomendasi {{ $i + 1 }}</p>
                                            <p class="text-xs text-indigo-600">Perusahaan Global, Jakarta</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">Baru</span>
                                </a>
                            @endfor
                        </div>
                        
                        <a href="{{ route('lowongans.pelamar_index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 mt-5 block text-center">
                            Lihat Lebih Banyak Lowongan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>