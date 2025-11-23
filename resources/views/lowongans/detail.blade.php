<x-app-layout>
    {{-- Memuat FontAwesome (Diasumsikan sudah ada di app-layout) --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb / Back Link --}}
            <div class="mb-4 px-4 sm:px-0">
                <a href="{{ route('lowongans.pelamar_index') }}"
                    class="inline-flex items-center text-gray-600 hover:text-indigo-600 transition duration-200 font-medium text-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Lowongan
                </a>
            </div>

            {{-- Bagian Pemberitahuan Sukses/Gagal Lamaran --}}
            @if (session('success') || session('error'))
                <div class="mb-6 p-4 text-sm {{ session('success') ? 'text-green-800 rounded-xl bg-green-100 border border-green-400' : 'text-red-800 rounded-xl bg-red-100 border border-red-400' }} shadow-md flex items-center gap-2">
                    <i class="fas {{ session('success') ? 'fa-check-circle' : 'fa-times-circle' }} text-lg"></i> 
                    {{ session('success') ?? session('error') }}
                </div>
            @endif

            {{-- 1. JOB HEADER CARD (Menggabungkan Judul, Logo, Gaji, Lokasi) --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 md:p-8 mb-8">
                <div class="flex flex-col sm:flex-row items-start justify-between">

                    <!-- KIRI: Logo, Posisi, Perusahaan, Lokasi -->
                    <div class="flex items-start space-x-4 flex-grow">
                        <!-- Logo Perusahaan -->
                        <div class="w-16 h-16 flex-shrink-0 border border-gray-200 rounded-xl flex items-center justify-center p-2 overflow-hidden">
                            <img src="https://placehold.co/64x64/4F46E5/ffffff?text={{ strtoupper(substr($lowongan->company->nama_perusahaan ?? 'C', 0, 1)) }}"
                                alt="Logo" class="w-full h-full object-contain rounded-lg"
                                onerror="this.onerror=null;this.src='https://placehold.co/64x64/4F46E5/ffffff?text={{ strtoupper(substr($lowongan->company->nama_perusahaan ?? 'C', 0, 1)) }}';">
                        </div>

                        <div>
                            <h1 class="text-2xl font-extrabold text-gray-900 mb-1 leading-tight">{{ $lowongan->posisi }}
                            </h1>
                            <p class="text-sm font-medium text-gray-600">{{ $lowongan->company->nama_perusahaan ?? 'Perusahaan' }}</p>

                            <div class="flex items-center space-x-4 text-sm text-gray-500 mt-2">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-indigo-500"></i>
                                    {{ $lowongan->lokasi_kantor ?? 'Lokasi Tidak Ditentukan' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- KANAN: Gaji & Status -->
                    <div class="mt-4 sm:mt-0 flex flex-col items-end space-y-2">
                        <p class="text-xl font-bold text-gray-900">
                            {{ $lowongan->gaji ?? 'Gaji Kompetitif' }}
                        </p>
                        @php
                            // Mengambil data tipe kerja
                            $tipeKerja = $lowongan->tipe_kerja ?? 'Full Time';
                            // Tentukan teks dan warna badge
                            $badgeText = $lowongan->status == 'Closed' ? 'Ditutup' : $tipeKerja;
                            $badgeClass = $lowongan->status == 'Open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                        @endphp
                        <span
                            class="px-3 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                            {{ $badgeText }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. GRID LAYOUT (Main Content & Sidebar) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- LEFT COLUMN: Deskripsi, Skills, Requirements --}}
                <div class="lg:col-span-2 space-y-6">

                    <!-- Job Description Card -->
                    <div class="bg-white shadow-xl rounded-2xl p-6 md:p-8 border border-gray-200">
                        <h3 class="text-2xl font-bold mb-4 border-b pb-2 border-gray-200 text-gray-900">
                            Job Description
                        </h3>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($lowongan->deskripsi)) !!}
                        </div>
                    </div>

                    {{-- KETERAMPILAN YANG DIBUTUHKAN --}}
                    <div class="bg-white shadow-xl rounded-2xl p-6 md:p-8 border border-gray-200">
                        <h3 class="text-2xl font-bold mb-4 border-b pb-2 border-gray-200 text-gray-900">
                            Keterampilan yang Dibutuhkan
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            @php
                                $keterampilan = explode(',', $lowongan->keterampilan ?? '');
                            @endphp

                            @forelse (array_filter($keterampilan) as $skill)
                                <span class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-full 
                                    bg-indigo-100 text-indigo-800 shadow-sm">
                                    <i class="fas fa-code mr-2 text-indigo-500"></i>
                                    {{ trim($skill) }}
                                </span>
                            @empty
                                <p class="text-gray-500 italic">Keterampilan yang dibutuhkan belum dimasukkan.</p>
                            @endforelse
                        </div>
                    </div>
                    
                    {{-- Persyaratan Tambahan (Simulasi/Placeholder) --}}
                    <div class="bg-white shadow-xl rounded-2xl p-6 md:p-8 border border-gray-200">
                        <h3 class="text-2xl font-bold mb-4 border-b pb-2 border-gray-200 text-gray-900">
                            Keterampilan, Pengetahuan & Pengalaman Tambahan
                        </h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                            <li>Memiliki pengalaman minimal 3 tahun di bidang terkait.</li>
                            <li>Kuasai bahasa pemrograman/tools yang relevan (misal: Python, Figma, dsb.).</li>
                            <li>Keterampilan komunikasi dan pemecahan masalah yang kuat.</li>
                            <li>Pendidikan S1/setara di bidang yang relevan.</li>
                        </ul>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Sidebar (Overview, Form Lamaran, Company Info) --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Job Overview / Ringkasan Pekerjaan --}}
                    <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold mb-4 border-b pb-2 border-gray-200 text-gray-900">
                            Job Overview
                        </h3>
                        <dl class="space-y-3 text-sm text-gray-700">
                            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                <dt class="font-medium flex items-center gap-2"><i class="fas fa-calendar-day text-indigo-500"></i> Tanggal Posting:</dt>
                                <dd>{{ $lowongan->created_at->format('d M Y') ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                <dt class="font-medium flex items-center gap-2"><i class="fas fa-clock text-indigo-500"></i> Tipe Kerja:</dt>
                                {{-- Menggunakan field tipe_kerja yang sebenarnya --}}
                                <dd>{{ $lowongan->tipe_kerja ?? 'Full-time' }}</dd>
                            </div>
                            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                <dt class="font-medium flex items-center gap-2"><i class="fas fa-sack-dollar text-indigo-500"></i> Gaji:</dt>
                                <dd class="font-bold text-green-600">{{ $lowongan->gaji ?? 'Kompetitif' }}</dd>
                            </div>
                            <div class="flex justify-between items-center pb-2">
                                <dt class="font-medium flex items-center gap-2"><i class="fas fa-hourglass-end text-indigo-500"></i> Batas Lamaran:</dt>
                                <dd>30 Nov 2025</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- COMPANY INFORMATION --}}
                    <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold mb-4 border-b pb-2 border-gray-200 text-gray-900">
                            Company Information
                        </h3>

                        {{-- Deskripsi Singkat Perusahaan --}}
                        <p class="text-sm text-gray-600 mb-4">
                            {{ $lowongan->company->deskripsi_singkat ?? 'Perusahaan ini bergerak di bidang Creative Agency.' }}
                        </p>

                        <dl class="space-y-2 text-sm text-gray-700">
                            <div class="flex justify-between items-center">
                                <dt class="font-medium">Nama:</dt>
                                <dd>{{ $lowongan->company->nama_perusahaan ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="font-medium">Web:</dt>
                                <dd>
                                    <a href="{{ $lowongan->company->website ?? '#' }}" target="_blank"
                                        class="text-indigo-600 hover:underline">
                                        {{ $lowongan->company->website ?? 'website-perusahaan.com' }}
                                    </a>
                                </dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="font-medium">Email:</dt>
                                <dd>{{ $lowongan->company->email_kontak ?? 'contact@company.com' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Formulir Kirim Lamaran --}}
                    <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200">
                        @if ($lowongan->status == 'Open')
                            <h4 class="text-xl font-bold mb-4 text-gray-900">Kirim Lamaran Anda</h4>

                            @if ($resumes->isEmpty())
                                <div class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-100 border border-yellow-400"
                                    role="alert">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Anda harus memiliki <a
                                        href="{{ route('resumes.create') }}"
                                        class="font-bold underline text-yellow-900 hover:text-yellow-700">Resume
                                        (CV)</a> sebelum bisa melamar.
                                </div>
                            @else
                                <form method="POST" action="{{ route('lamaran.store') }}">
                                    @csrf

                                    <input type="hidden" name="id_lowongan" value="{{ $lowongan->id_lowongan }}">

                                    <div class="mb-4">
                                        <label for="id_resume" class="block font-medium text-sm text-gray-700 mb-2">Pilih
                                            Resume/CV:</label>
                                        <select name="id_resume" id="id_resume" required
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm block w-full transition text-gray-900">
                                            <option value="" disabled selected>-- Pilih Resume Anda --</option>
                                            @foreach ($resumes as $resume)
                                                <option value="{{ $resume->id_resume }}">{{ $resume->nama_resume }}
                                                    ({{ $resume->created_at->format('d M Y') }})</option>
                                            @endforeach
                                        </select>
                                        @error('id_resume')
                                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-base text-white tracking-wider shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 transform hover:scale-[1.01]">
                                        <i class="fas fa-paper-plane mr-2"></i> Kirim Lamaran
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="p-4 text-sm text-red-800 rounded-lg bg-red-100 border border-red-400" role="alert">
                                <i class="fas fa-lock mr-1"></i> Lowongan ini sudah ditutup dan tidak menerima lamaran.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>