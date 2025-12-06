<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Verifikasi Perusahaan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Verifikasi Perusahaan</h1>
                <p class="text-gray-600 mt-2">Kelola persetujuan registrasi perusahaan baru</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 text-sm bg-green-100 text-green-800 border border-green-400 rounded-2xl shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Tabs -->
            <div class="mb-8 flex gap-6 border-b-2 border-gray-200">
                <a href="{{ route('admin.companies.index') }}" class="pb-3 font-semibold border-b-2 transition @if(!request('status')) border-indigo-600 text-indigo-600 @else border-transparent text-gray-600 hover:text-gray-900 @endif">
                    Semua ({{ $companies->total() }})
                </a>
                <a href="{{ route('admin.companies.index', ['status' => 'unverified']) }}" class="pb-3 font-semibold border-b-2 transition @if(request('status') === 'unverified') border-indigo-600 text-indigo-600 @else border-transparent text-gray-600 hover:text-gray-900 @endif">
                    Belum Diverifikasi
                </a>
                <a href="{{ route('admin.companies.index', ['status' => 'verified']) }}" class="pb-3 font-semibold border-b-2 transition @if(request('status') === 'verified') border-indigo-600 text-indigo-600 @else border-transparent text-gray-600 hover:text-gray-900 @endif">
                    Terverifikasi
                </a>
            </div>

            <!-- Search -->
            <form method="GET" action="{{ route('admin.companies.index') }}" class="mb-8 flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama perusahaan atau email..."
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-900">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-md">Cari</button>
            </form>

            <!-- Companies Table -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                @if($companies->count() > 0)
                    <table class="min-w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Nama Perusahaan</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">No Telp</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Status</th>
                                <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($companies as $company)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $company->nama_perusahaan }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $company->user->email }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $company->no_telp_perusahaan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($company->is_verified)
                                            <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold border border-green-300">
                                                ✓ Terverifikasi
                                            </span>
                                        @else
                                            <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold border border-yellow-300">
                                                ⏳ Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.companies.show', $company) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="bg-white px-6 py-4 border-t border-gray-200">
                        {{ $companies->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <p class="text-gray-500 text-lg">Tidak ada perusahaan yang ditemukan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
