<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Lowongan Pekerjaan') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $lowongans->total() ?? 0 }} lowongan tersedia untuk Anda
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Quick Search Bar --}}
            <div class="mb-6 bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h4 class="font-semibold text-gray-900 mb-4">Cari Lowongan</h4>
                <form method="GET" action="{{ route('lowongans.pelamar_index') }}" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" placeholder="Cari skill, posisi..."
                        value="{{ request('search') }}"
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 text-gray-900" />
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Cari
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-12 gap-6">

                <!-- Sidebar Filter -->
                <div class="col-span-12 lg:col-span-3">
                    <div class="sticky top-4 bg-white rounded-2xl shadow-lg border border-gray-200 p-6 space-y-6">

                        <div>
                            <h3 class="font-bold text-lg text-gray-900 mb-4">Filter</h3>
                        </div>

                        <!-- Job Type -->
                        <form method="GET" action="{{ route('lowongans.pelamar_index') }}" id="filterForm">
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-3">Tipe Pekerjaan</h4>
                                <div class="space-y-2 text-sm">
                                    @php $jobTypes = ['Full Time', 'Part Time', 'Remote', 'Freelance']; @endphp
                                    @foreach ($jobTypes as $type)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="job_type[]" value="{{ $type }}"
                                                @checked(in_array($type, request('job_type', [])))
                                                class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300">
                                            <span class="text-gray-700">{{ $type }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="font-semibold text-gray-800 mb-3">Status</h4>
                                <div class="space-y-2 text-sm">
                                    <label class="flex items-center">
                                        <input type="radio" name="status" value="Open"
                                            @checked(request('status') == 'Open')
                                            class="rounded-full text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300">
                                        <span class="text-gray-700">Dibuka</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="status" value="Closed"
                                            @checked(request('status') == 'Closed')
                                            class="rounded-full text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300">
                                        <span class="text-gray-700">Ditutup</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="status" value=""
                                            @checked(request('status') == '')
                                            class="rounded-full text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300">
                                        <span class="text-gray-700">Semua</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Skill Match -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <label class="flex items-center">
                                    <input type="checkbox" name="match" value="true"
                                        @checked(request('match') === 'true')
                                        class="rounded text-indigo-600 focus:ring-indigo-500 mr-2 border-gray-300">
                                    <span class="text-sm font-medium text-gray-700">Sesuai skill saya</span>
                                </label>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                                <button type="submit"
                                    class="w-full px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                                    Terapkan Filter
                                </button>
                                <a href="{{ route('lowongans.pelamar_index') }}"
                                    class="block text-center px-4 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                                    Reset
                                </a>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Daftar Lowongan -->
                <div class="col-span-12 lg:col-span-9 space-y-4">
                    @forelse ($lowongans as $lowongan)
                        <a href="{{ route('lowongans.detail', $lowongan->id_lowongan) }}"
                            class="block bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl hover:border-indigo-300 transition duration-300 p-6">
                            <div class="flex gap-4">
                                {{-- Company Logo --}}
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($lowongan->company->nama_perusahaan ?? 'C', 0, 1)) }}
                                    </div>
                                </div>

                                {{-- Job Info --}}
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">
                                                {{ $lowongan->judul }}
                                            </h3>
                                            <p class="text-sm font-semibold text-indigo-600">
                                                {{ $lowongan->company->nama_perusahaan ?? 'Perusahaan' }}
                                            </p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-bold flex-shrink-0
                                            {{ $lowongan->status == 'Open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $lowongan->status == 'Open' ? 'Dibuka' : 'Ditutup' }}
                                        </span>
                                    </div>

                                    {{-- Location & Salary --}}
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"></path>
                                            </svg>
                                            {{ $lowongan->lokasi_kantor ?? 'Lokasi tidak ditentukan' }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $lowongan->gaji ?? 'Gaji kompetitif' }}
                                        </div>
                                    </div>

                                    {{-- Skills --}}
                                    @if ($lowongan->skills->count() > 0)
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            @foreach ($lowongan->skills->take(5) as $skill)
                                                <span class="px-3 py-1 text-xs font-medium bg-indigo-50 text-indigo-700 rounded-full">
                                                    {{ $skill->nama_skill }}
                                                </span>
                                            @endforeach
                                            @if ($lowongan->skills->count() > 5)
                                                <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                                                    +{{ $lowongan->skills->count() - 5 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Description --}}
                                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                        {{ $lowongan->deskripsi }}
                                    </p>

                                    {{-- Meta Info --}}
                                    <div class="flex justify-between items-center text-xs text-gray-500 pt-3 border-t border-gray-100">
                                        <span class="font-medium">{{ $lowongan->tipe_kerja ?? 'Full Time' }}</span>
                                        <span>{{ $lowongan->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Tidak ada lowongan</h3>
                            <p class="text-gray-600">Coba sesuaikan pencarian atau filter Anda</p>
                        </div>
                    @endforelse
                </div>

            </div>

            {{-- Pagination --}}
            @if ($lowongans->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $lowongans->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
