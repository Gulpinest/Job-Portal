<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ $lowongan->posisi }} - {{ $lowongan->judul }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">

                {{-- Breadcrumb / Back Link --}}
                <div class="mb-4">
                    <a href="{{ route('lowongans.index') }}"
                        class="inline-flex items-center text-gray-600 hover:text-indigo-600 transition duration-200 font-medium text-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar Lowongan
                    </a>
                </div>

                {{-- HEADER CARD --}}
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 md:p-8">
                    <div class="flex flex-col sm:flex-row items-start justify-between">
                        <div class="flex-grow">
                            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $lowongan->posisi }}</h1>
                            <h2 class="text-lg font-semibold text-gray-600 mb-4">{{ $lowongan->judul }}</h2>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $statusClass = $lowongan->status == 'Open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                                    $statusText = $lowongan->status == 'Open' ? 'Dibuka' : 'Ditutup';
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                    <i class="fas {{ $lowongan->status == 'Open' ? 'fa-check-circle' : 'fa-lock' }} mr-1"></i>
                                    {{ $statusText }}
                                </span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                    <i class="fas fa-briefcase mr-1"></i> {{ $lowongan->tipe_kerja ?? 'Full-time' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0 text-right">
                            <p class="text-2xl font-bold text-gray-900">{{ $lowongan->gaji ?? 'Kompetitif' }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $lowongan->lokasi_kantor ?? 'Remote' }}</p>
                        </div>
                    </div>
                </div>

                {{-- MAIN CONTENT GRID --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- LEFT COLUMN: 2/3 --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Job Description --}}
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                            <h3 class="text-2xl font-bold mb-4 border-b pb-2 border-gray-200 text-gray-900">
                                Deskripsi Pekerjaan
                            </h3>
                            <div class="prose max-w-none text-gray-700 leading-relaxed">
                                {!! nl2br(e($lowongan->deskripsi)) !!}
                            </div>
                        </div>

                        {{-- Required Skills --}}
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                            <h3 class="text-2xl font-bold mb-4 border-b pb-2 border-gray-200 text-gray-900">
                                Keterampilan yang Dibutuhkan
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                @forelse ($lowongan->skills as $skill)
                                    <span class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-full
                                        bg-indigo-100 text-indigo-800 shadow-sm">
                                        <i class="fas fa-code mr-2 text-indigo-500"></i>
                                        {{ $skill->nama_skill }}
                                    </span>
                                @empty
                                    <p class="text-gray-500 italic">Tidak ada keterampilan yang ditentukan.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Additional Requirements --}}
                        @if (!empty($lowongan->persyaratan_tambahan))
                            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                                <h3 class="text-2xl font-bold mb-4 border-b pb-2 border-gray-200 text-gray-900">
                                    Persyaratan Tambahan
                                </h3>
                                <div class="prose max-w-none text-gray-700 leading-relaxed">
                                    {!! nl2br(e($lowongan->persyaratan_tambahan)) !!}
                                </div>
                            </div>
                        @endif

                        {{-- APPLICANTS SECTION --}}
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                            <h3 class="text-2xl font-bold mb-4 border-b pb-2 border-gray-200 text-gray-900">
                                Pelamar Lowongan Ini
                            </h3>

                            {{-- Stats Cards --}}
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="bg-blue-50 rounded-lg p-4 text-center border border-blue-200">
                                    <p class="text-3xl font-bold text-blue-600">{{ count($lamarans) }}</p>
                                    <p class="text-sm text-blue-700 font-medium">Total Pelamar</p>
                                </div>
                                <div class="bg-yellow-50 rounded-lg p-4 text-center border border-yellow-200">
                                    <p class="text-3xl font-bold text-yellow-600">{{ $pendingCount }}</p>
                                    <p class="text-sm text-yellow-700 font-medium">Pending Review</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4 text-center border border-green-200">
                                    <p class="text-3xl font-bold text-green-600">{{ $acceptedCount }}</p>
                                    <p class="text-sm text-green-700 font-medium">Diterima</p>
                                </div>
                            </div>

                            {{-- Applicants List --}}
                            @if ($lamarans->isEmpty())
                                <div class="text-center py-8">
                                    <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 font-medium">Belum ada pelamar untuk lowongan ini.</p>
                                </div>
                            @else
                                <div class="space-y-3 max-h-96 overflow-y-auto">
                                    @foreach ($lamarans as $lamaran)
                                        @php
                                            $statusColors = [
                                                'Pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                'Accepted' => 'bg-green-100 text-green-700 border-green-200',
                                                'Rejected' => 'bg-red-100 text-red-700 border-red-200',
                                            ];
                                            $statusClass = $statusColors[$lamaran->status_ajuan] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                            $statusIcon = [
                                                'Pending' => 'fa-hourglass-end',
                                                'Accepted' => 'fa-check-circle',
                                                'Rejected' => 'fa-times-circle',
                                            ][$lamaran->status_ajuan] ?? 'fa-question-circle';
                                        @endphp
                                        <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between hover:bg-gray-50 transition">
                                            <div class="flex-grow">
                                                <p class="font-semibold text-gray-900">{{ $lamaran->pelamar->nama_lengkap ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-600">{{ $lamaran->pelamar->email ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    Melamar: {{ $lamaran->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $statusClass }}">
                                                    <i class="fas {{ $statusIcon }} mr-1"></i>{{ $lamaran->status_ajuan }}
                                                </span>
                                                <a href="{{ route('company.lamarans.show', $lamaran->id_lamaran) }}"
                                                    class="px-3 py-1 text-xs font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                                    <i class="fas fa-eye mr-1"></i>Lihat
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Link to manage all applications --}}
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <a href="{{ route('company.lamarans.index') }}"
                                        class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold text-sm">
                                        <i class="fas fa-arrow-right mr-2"></i>
                                        Kelola semua aplikasi
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- RIGHT COLUMN: 1/3 SIDEBAR --}}
                    <div class="lg:col-span-1 space-y-6">

                        {{-- INTERVIEW SCHEDULING CARD (HIGH PRIORITY) --}}
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl shadow-xl border-2 border-indigo-300 p-6">
                            @if ($interviewScheduled)
                                {{-- Interview Already Scheduled --}}
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="bg-green-500 rounded-full p-3 text-white">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Jadwal Wawancara Sudah Dibuat</h4>
                                        <p class="text-sm text-gray-700 mt-1">
                                            Wawancara untuk lowongan ini sudah dijadwalkan.
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('interview-schedules.index') }}"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 transition">
                                    <i class="fas fa-calendar mr-2"></i>
                                    Lihat Jadwal Wawancara
                                </a>
                            @elseif ($acceptedCount === 0)
                                {{-- No Accepted Applications --}}
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="bg-gray-500 rounded-full p-3 text-white">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Belum Ada Pelamar Diterima</h4>
                                        <p class="text-sm text-gray-700 mt-1">
                                            Terima aplikasi terlebih dahulu sebelum menjadwalkan wawancara.
                                        </p>
                                    </div>
                                </div>
                                <button disabled
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-400 text-white rounded-xl font-semibold text-sm cursor-not-allowed opacity-50">
                                    <i class="fas fa-calendar-times mr-2"></i>
                                    Jadwalkan Wawancara
                                </button>
                            @else
                                {{-- Ready to Schedule Interview --}}
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="bg-blue-500 rounded-full p-3 text-white">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Siap Menjadwalkan Wawancara</h4>
                                        <p class="text-sm text-gray-700 mt-1">
                                            Anda memiliki <strong>{{ $acceptedCount }}</strong> pelamar yang diterima.
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('interview-schedules.create', $lowongan->id_lowongan) }}"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white rounded-xl font-semibold text-sm hover:bg-indigo-700 transition shadow-lg">
                                    <i class="fas fa-plus mr-2"></i>
                                    Jadwalkan Wawancara
                                </a>
                            @endif
                        </div>

                        {{-- Job Overview --}}
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                            <h3 class="text-xl font-bold mb-4 border-b pb-2 border-gray-200 text-gray-900">
                                Ringkasan Lowongan
                            </h3>
                            <dl class="space-y-3 text-sm text-gray-700">
                                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                    <dt class="font-medium flex items-center gap-2"><i class="fas fa-calendar text-indigo-500"></i> Diposting:</dt>
                                    <dd>{{ $lowongan->created_at->format('d M Y') }}</dd>
                                </div>
                                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                    <dt class="font-medium flex items-center gap-2"><i class="fas fa-clock text-indigo-500"></i> Tipe Kerja:</dt>
                                    <dd>{{ $lowongan->tipe_kerja ?? 'Full-time' }}</dd>
                                </div>
                                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                    <dt class="font-medium flex items-center gap-2"><i class="fas fa-map-marker-alt text-indigo-500"></i> Lokasi:</dt>
                                    <dd>{{ $lowongan->lokasi_kantor ?? 'Remote' }}</dd>
                                </div>
                                <div class="flex justify-between items-center pb-2">
                                    <dt class="font-medium flex items-center gap-2"><i class="fas fa-sack-dollar text-indigo-500"></i> Gaji:</dt>
                                    <dd class="font-bold text-green-600">{{ $lowongan->gaji ?? 'Kompetitif' }}</dd>
                                </div>
                            </dl>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 space-y-2">
                            <h3 class="text-xl font-bold mb-4 border-b pb-2 border-gray-200 text-gray-900">
                                Aksi
                            </h3>
                            <a href="{{ route('lowongans.edit', $lowongan->id_lowongan) }}"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-500 text-white rounded-xl font-semibold text-sm hover:bg-yellow-600 transition">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Lowongan
                            </a>
                            <form method="POST" action="{{ route('lowongans.destroy', $lowongan->id_lowongan) }}"
                                onsubmit="return confirm('PERINGATAN: Menghapus lowongan ini akan menghapus semua data pelamar. Apakah Anda yakin?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white rounded-xl font-semibold text-sm hover:bg-red-700 transition">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    Hapus Lowongan
                                </button>
                            </form>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</x-app-layout>
