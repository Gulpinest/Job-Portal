<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center pt-4 pb-2 border-b border-gray-200">
                <!-- Job Count & Title -->
                <div class="flex-1">
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                        {{ __('Lowongan Pekerjaan Tersedia') }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ count($lowongans) }} Lowongan ditemukan.
                    </p>
                </div>

                <!-- Sort By Dropdown -->
                <div class="mt-4 md:mt-0 flex items-center space-x-2">
                    <label for="sort" class="text-sm font-medium text-gray-700">Sort by</label>
                    <select id="sort" name="sort"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option>None</option>
                        <option>Terbaru</option>
                        <option>Gaji Tertinggi</option>
                    </select>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        
        <!-- SEARCH BAR SKILL (DIPINDAHKAN KE ATAS LIST) -->
        <div class="mb-6 bg-white p-4 rounded-xl shadow-lg border border-gray-200">
             <h4 class="font-semibold text-gray-800 mb-2">Cari Lowongan Berdasarkan Skill atau Lokasi</h4>
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" placeholder="Cari Skill Utama, Posisi..."
                    class="block w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900" />
                <input type="text" placeholder="Lokasi (Kota atau Remote)"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900" />
            </div>
        </div>
        
        <div class="grid grid-cols-12 gap-8">

            <!-- Kiri: Filter Sidebar -->
            <div class="col-span-12 lg:col-span-3">
                <div class="sticky top-4 space-y-6 p-6 bg-white border border-gray-200 rounded-xl shadow-lg">

                    <h3 class="font-bold text-lg text-gray-900 border-b pb-3 -mt-2">Filter Jobs</h3>

                    <!-- Job Type -->
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Job Type</h4>
                        <div class="space-y-1 text-sm text-gray-700">
                            <label class="flex items-center"><input type="checkbox" checked class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> Full Time</label>
                            <label class="flex items-center"><input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> Part Time</label>
                            <label class="flex items-center"><input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> Remote</label>
                            <label class="flex items-center"><input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> Freelance</label>
                        </div>
                    </div>
                    
                    <!-- Experience -->
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Experience</h4>
                        <div class="space-y-1 text-sm text-gray-700">
                            <label class="flex items-center"><input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> 1-2 Years</label>
                            <label class="flex items-center"><input type="checkbox" checked class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> 2-3 Years</label>
                            <label class="flex items-center"><input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> 3-6 Years</label>
                            <label class="flex items-center"><input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> 6-more..</label>
                        </div>
                    </div>

                    <!-- Posted Within -->
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Posted Within</h4>
                        <div class="space-y-1 text-sm text-gray-700">
                            <label class="flex items-center"><input type="checkbox" checked class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> Any</label>
                            <label class="flex items-center"><input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> Today</label>
                            <label class="flex items-center"><input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> Last 2 days</label>
                            <label class="flex items-center"><input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> Last 5 days</label>
                            <label class="flex items-center"><input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300"> Last 10 days</label>
                        </div>
                    </div>
                    
                    <!-- TOMBOL AKSI FILTER (BARU DITAMBAHKAN) -->
                    <div class="pt-4 border-t border-gray-100 space-y-3">
                        <button type="button" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                            Terapkan Filter
                        </button>
                        <button type="button" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-sm">
                            Reset Filter
                        </button>
                    </div>

                </div>
            </div>

            <!-- Kanan: Daftar Lowongan -->
            <div class="col-span-12 lg:col-span-9 space-y-4">
                @forelse ($lowongans as $lowongan)
                    <a href="{{ route('lowongans.detail', $lowongan->id_lowongan) }}"
                        class="block p-5 bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition duration-300 ease-in-out hover:border-indigo-400">
                        <div class="flex justify-between items-start">

                            <!-- Kiri: Logo, Judul, Perusahaan, Lokasi, Skills -->
                            <div class="flex space-x-4 flex-grow">

                                <!-- Logo Perusahaan (Placeholder) -->
                                <div
                                    class="w-12 h-12 flex-shrink-0 bg-indigo-500 rounded-xl flex items-center justify-center text-white font-bold text-lg overflow-hidden border border-gray-200">
                                    {{-- Gunakan huruf pertama nama perusahaan sebagai logo placeholder --}}
                                    {{ strtoupper(substr($lowongan->company->nama_perusahaan ?? 'C', 0, 1)) }}
                                </div>

                                <div>
                                    <!-- Judul Lowongan -->
                                    <h3 class="text-xl font-extrabold text-gray-900 mb-1 leading-tight">
                                        {{ $lowongan->judul }}
                                    </h3>

                                    <!-- Detail Perusahaan -->
                                    <p class="text-sm font-semibold text-indigo-600 mb-2">
                                        {{ $lowongan->company->nama_perusahaan ?? 'Perusahaan Tidak Diketahui' }}
                                    </p>

                                    <!-- Lokasi Kantor -->
                                    <p class="flex items-center text-sm text-gray-600 mb-3">
                                        <!-- Ikon Lokasi -->
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $lowongan->lokasi_kantor ?? 'Remote / Lokasi Tidak Ditentukan' }}
                                    </p>

                                    <!-- SKILLS (BARU DITAMBAHKAN - Menggunakan array placeholder) -->
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @php
                                            // ASUMSI: $lowongan->skills adalah array/koleksi dari string skill.
                                            // Karena field skills tidak ada di kode awal, kita gunakan data placeholder.
                                            $sampleSkills = ['Laravel', 'REST API', 'JavaScript', 'Database', 'Agile'];
                                            // Ambil 3 skill teratas sebagai contoh
                                            $skills = $lowongan->skills ?? array_slice($sampleSkills, 0, rand(2, 4));
                                        @endphp
                                        @foreach ($skills as $skill)
                                            <span
                                                class="px-3 py-0.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">
                                                {{ $skill }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Kanan: Gaji, Tipe, Waktu Posting -->
                            <div class="flex flex-col items-end flex-shrink-0 ml-4">
                                <!-- Gaji (Placeholder) -->
                                <p class="text-lg font-bold text-gray-900 mb-2">$ {{ number_format(rand(3000, 6000)) }} - $
                                    {{ number_format(rand(7000, 12000)) }}
                                </p>

                                <!-- Badge Tipe Pekerjaan/Status -->
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full tracking-wider uppercase {{ $lowongan->status == 'Open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    <!-- Menggunakan 'Full Time' sebagai placeholder tipe, sesuaikan dengan data Lowongan Anda -->
                                    {{ $lowongan->status == 'Open' ? 'Full Time' : 'Tutup' }}
                                </span>

                                <!-- Waktu Posting (Placeholder) -->
                                <p class="text-xs text-gray-400 mt-2">7 hours ago</p>
                            </div>

                        </div>
                    </a>
                @empty
                    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-lg text-center py-12">
                        <p class="text-gray-900 font-semibold">Belum ada lowongan pekerjaan yang tersedia saat ini.</p>
                        <p class="text-sm text-gray-500">Coba sesuaikan filter pencarian Anda.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>