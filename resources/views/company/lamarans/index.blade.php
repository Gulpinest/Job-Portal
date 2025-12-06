<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Kelola Lamaran') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">

                {{-- Statistics Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Lamaran -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 font-medium text-sm">Total Lamaran</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalLamarans }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 font-medium text-sm">Menunggu</p>
                                <p class="text-3xl font-extrabold text-yellow-600 mt-1">{{ $pendingLamarans }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Accepted -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 font-medium text-sm">Diterima</p>
                                <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $acceptedLamarans }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Rejected -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 font-medium text-sm">Ditolak</p>
                                <p class="text-3xl font-extrabold text-red-600 mt-1">{{ $rejectedLamarans }}</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter & Search Bar --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <form method="GET" action="{{ route('company.lamarans.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pelamar</label>
                                <input type="text" name="search" placeholder="Nama atau email..."
                                    value="{{ request('search') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900" />
                            </div>

                            <!-- Filter by Lowongan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lowongan</label>
                                <select name="id_lowongan"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">
                                    <option value="">-- Semua Lowongan --</option>
                                    @foreach($lowongans as $lowongan)
                                        <option value="{{ $lowongan->id_lowongan }}" {{ request('id_lowongan') == $lowongan->id_lowongan ? 'selected' : '' }}>
                                            {{ $lowongan->judul }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter by Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">
                                    <option value="">-- Semua Status --</option>
                                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Accepted" {{ request('status') === 'Accepted' ? 'selected' : '' }}>Diterima</option>
                                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-end gap-2">
                                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                                    Cari
                                </button>
                                <a href="{{ route('company.lamarans.index') }}" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition text-center">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Lamarans Table --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Pelamar</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Lowongan</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Resume</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-900">Status</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Tanggal</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-900">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($lamarans as $lamaran)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $lamaran->pelamar->user->name ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-600">{{ $lamaran->pelamar->user->email ?? '-' }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-900">{{ $lamaran->lowongan->judul }}</p>
                                            <p class="text-sm text-gray-600">{{ $lamaran->lowongan->posisi }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-900">{{ $lamaran->resume->nama_resume ?? 'Tidak ada' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $statusColor = match($lamaran->status_ajuan) {
                                                    'Pending' => 'bg-yellow-100 text-yellow-700',
                                                    'Accepted' => 'bg-green-100 text-green-700',
                                                    'Rejected' => 'bg-red-100 text-red-700',
                                                    default => 'bg-gray-100 text-gray-700'
                                                };
                                                $statusText = match($lamaran->status_ajuan) {
                                                    'Pending' => 'Menunggu',
                                                    'Accepted' => 'Diterima',
                                                    'Rejected' => 'Ditolak',
                                                    default => $lamaran->status_ajuan
                                                };
                                            @endphp
                                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full {{ $statusColor }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $lamaran->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('company.lamarans.show', $lamaran->id_lamaran) }}"
                                                class="inline-flex items-center px-3 py-1 text-sm font-semibold text-indigo-600 hover:text-indigo-900">
                                                Lihat →
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-600">
                                            Tidak ada lamaran
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if ($lamarans->hasPages())
                    <div class="mt-8 space-y-4">
                        <div class="text-sm text-gray-600 text-center">
                            Menampilkan <span class="font-semibold">{{ $lamarans->firstItem() }}</span>
                            hingga
                            <span class="font-semibold">{{ $lamarans->lastItem() }}</span>
                            dari
                            <span class="font-semibold">{{ $lamarans->total() }}</span>
                            lamaran
                        </div>
                        <div class="flex justify-center">
                            {{ $lamarans->withQueryString()->links() }}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
