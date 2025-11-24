<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center pt-4 pb-2 border-b border-gray-200">
                <!-- Job Count & Title -->
                <div class="flex-1">
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                        {{ __('Lowongan Pekerjaan Tersedia') }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $lowongans->total() ?? 0 }} Lowongan ditemukan.
                    </p>
                </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                {{-- FORM PENCARIAN & FILTER STATUS --}}
                <form method="GET" action="{{ route('lowongans.pelamar_index') }}" class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-3 md:space-y-0">
                        {{-- Search Input --}}
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul atau Posisi..."
                            class="w-full md:w-2/3 px-4 py-2 rounded-lg border border-gray-300 focus:ring focus:ring-indigo-200 text-gray-800">

                        {{-- Filter Status --}}
                        <select name="status" class="w-full md:w-1/3 px-4 py-2 rounded-lg border border-gray-300 text-gray-800">
                            <option value="">Semua Status</option>
                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>

                             {{-- Checkbox Skill Match --}}
                        <div class="flex items-center gap-2 text-sm">
                            <input type="checkbox" 
                                name="match" 
                                value="true" 
                                id="match" 
                                {{ request('match') === 'true' ? 'checked' : '' }}
                                class="w-4 h-4 accent-indigo-500">
                            <label for="match" class="text-gray-300">Tampilkan yang sesuai skill saya</label>
                        </div>
                        {{-- Tombol Submit --}}
                        <button type="submit"
                            class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Cari
                        </button>
                    </div>
                </form>

                {{-- LIST LOWONGAN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($lowongans as $lowongan)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col justify-between">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-bold">{{ $lowongan->judul }}</h3>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $lowongan->status == 'Open' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ $lowongan->status }}
                                    </span>
                                </div>

                                <p class="text-sm text-indigo-600 dark:text-indigo-400 mt-1 font-semibold">
                                    {{ $lowongan->company->nama_perusahaan ?? 'Perusahaan Tidak Diketahui' }}
                                </p>

                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $lowongan->posisi }}</p>
                                <p class="text-sm mt-4 line-clamp-3">{{ $lowongan->deskripsi }}</p>
                            </div>
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end items-center space-x-3">
                                <a href="{{ route('lowongans.detail', $lowongan->id_lowongan) }}"
                                    class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-bold">Lihat Detail & Lamar</a>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                            <p>Tidak ada lowongan yang sesuai dengan pencarian atau filter.</p>
                        </div>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                <div class="mt-6">
                    {{ $lowongans->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">

        {{-- WRAPPER FORM UNTUK FILTER DAN SEARCH --}}
        <form method="GET" action="{{ route('lowongans.pelamar_index') }}">

            <!-- SEARCH BAR SKILL (DIPINDAHKAN KE ATAS LIST) -->
            <div class="mb-6 bg-white p-4 rounded-xl shadow-lg border border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-2">Cari Lowongan Berdasarkan Skill atau Lokasi</h4>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search_keyword" placeholder="Cari Skill Utama, Posisi..."
                        value="{{ request('search_keyword') }}"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900" />
                    <input type="text" name="search_location" placeholder="Lokasi (Kota atau Remote)"
                        value="{{ request('search_location') }}"
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
                                @php $jobTypes = ['Full Time', 'Part Time', 'Remote', 'Freelance']; @endphp
                                @foreach ($jobTypes as $type)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="job_type[]" value="{{ $type }}"
                                            @checked(in_array($type, request('job_type', [])))
                                            class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300">
                                        {{ $type }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Experience -->
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Experience</h4>
                            <div class="space-y-1 text-sm text-gray-700">
                                @php $experiences = ['1-2 Years', '2-3 Years', '3-6 Years', '6-more..']; @endphp
                                @foreach ($experiences as $exp)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="experience[]" value="{{ $exp }}"
                                            @checked(in_array($exp, request('experience', [])))
                                            class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300">
                                        {{ $exp }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Posted Within -->
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Posted Within</h4>
                            <div class="space-y-1 text-sm text-gray-700">
                                @php $postedWithinOptions = ['Any', 'Today', 'Last 2 days', 'Last 5 days', 'Last 10 days']; @endphp
                                @foreach ($postedWithinOptions as $option)
                                    <label class="flex items-center">
                                        <input type="radio" name="posted_within" value="{{ $option }}"
                                            @checked(request('posted_within') == $option || (!request('posted_within') && $option == 'Any'))
                                            class="rounded-full text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300">
                                        {{ $option }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- TOMBOL AKSI FILTER -->
                        <div class="pt-4 border-t border-gray-100 space-y-3">
                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                                Terapkan Filter
                            </button>
                            <button type="button"
                                onclick="window.location.href = '{{ route('lowongans.pelamar_index') }}';"
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

                                        <!-- SKILLS (Menggunakan data actual model) -->
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @php
                                                // Mengambil data keterampilan dari model lowongan (string koma-separated)
                                                $skills = is_string($lowongan->keterampilan) ? array_map('trim', explode(',', $lowongan->keterampilan)) : [];
                                                // Fallback untuk demo jika field kosong (Hanya untuk simulasi, hapus di produksi)
                                                if (empty(array_filter($skills))) {
                                                    $sampleSkills = ['Laravel', 'REST API', 'JavaScript', 'Agile'];
                                                    $skills = array_slice($sampleSkills, 0, rand(2, 4));
                                                }
                                            @endphp
                                            @foreach (array_filter($skills) as $skill)
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
                                    <!-- Gaji (Menggunakan data actual model) -->
                                    <p class="text-lg font-bold text-gray-900 mb-2">
                                        {{ $lowongan->gaji ?? 'Gaji Kompetitif' }}
                                    </p>

                                    <!-- Badge Tipe Pekerjaan/Status -->
                                    @php
                                        // Tentukan teks dan warna badge berdasarkan TIPE KERJA (Jika Open) atau Status (Jika Closed)
                                        $displayTipe = $lowongan->tipe_kerja ?? 'Full Time';
                                        $badgeText = $lowongan->status == 'Closed' ? 'Ditutup' : $displayTipe;
                                        $badgeClass = $lowongan->status == 'Open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                                    @endphp
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full tracking-wider uppercase {{ $badgeClass }}">
                                        {{ $badgeText }}
                                    </span>

                                    <!-- Waktu Posting (Placeholder) -->
                                    <p class="text-xs text-gray-400 mt-2">
                                        {{ $lowongan->created_at->diffForHumans() ?? '7 hours ago' }}</p>
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

                {{-- PAGINATION LINKS --}}
                @if ($lowongans->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $lowongans->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </form> {{-- END FORM --}}
    </div>
</x-app-layout>
