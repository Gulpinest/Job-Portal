<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- 1. HEADER & STATUS -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">Halo, {{ Auth::user()->name }}!</h3>
                <p class="text-md text-gray-500">Selamat datang di Admin Dashboard. Kelola sistem aplikasi dari sini.</p>

                {{-- Admin status info --}}
                <div class="mt-4 p-3 bg-indigo-50 border border-indigo-200 rounded-xl flex justify-between items-center">
                    <span class="text-sm font-medium text-indigo-700">
                        Dashboard admin untuk manajemen skill, verifikasi company, dan monitoring sistem.
                    </span>
                </div>
            </div>

            <!-- 2. STATISTICS SUMMARY CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">

                {{-- Card 1: Total Users --}}
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $totalUsers }}</p>
                </div>

                {{-- Card 2: Total Pelamars --}}
                <div class="bg-green-50 rounded-2xl p-6 shadow-md border border-green-200">
                    <p class="text-sm font-medium text-green-700">Total Pelamar</p>
                    <p class="text-3xl font-extrabold text-green-800 mt-1">{{ $pelamars }}</p>
                </div>

                {{-- Card 3: Total Companies --}}
                <div class="bg-purple-50 rounded-2xl p-6 shadow-md border border-purple-200">
                    <p class="text-sm font-medium text-purple-700">Total Perusahaan</p>
                    <p class="text-3xl font-extrabold text-purple-800 mt-1">{{ $companies }}</p>
                </div>

                {{-- Card 4: Pending Verification --}}
                <div class="bg-yellow-50 rounded-2xl p-6 shadow-md border border-yellow-200">
                    <p class="text-sm font-medium text-yellow-700">Menunggu Verifikasi</p>
                    <p class="text-3xl font-extrabold text-yellow-800 mt-1">{{ $pendingCompanies }}</p>
                </div>

                {{-- Card 5: Verified Companies --}}
                <div class="bg-indigo-50 rounded-2xl p-6 shadow-md border border-indigo-200">
                    <p class="text-sm font-medium text-indigo-700">Terverifikasi</p>
                    <p class="text-3xl font-extrabold text-indigo-800 mt-1">{{ $verifiedCompanies }}</p>
                </div>
            </div>

            <!-- 3. MAIN CONTENT: Quick Actions & Companies Verification -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left Column: Quick Actions --}}
                <div class="lg:col-span-1">

                    <!-- Quick Action Buttons -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 space-y-3">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">Aksi Cepat</h4>
                        <a href="{{ route('admin.skills.index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-indigo-600 text-white font-semibold text-sm rounded-lg hover:bg-indigo-700 transition shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Kelola Skill
                        </a>
                        <a href="{{ route('admin.companies.index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 transition shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Verifikasi Perusahaan
                        </a>
                        <a href="{{ route('admin.users') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-600 text-white font-semibold text-sm rounded-lg hover:bg-gray-700 transition shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Kelola Pengguna
                        </a>
                    </div>

                </div>

                {{-- Right Column: Companies Need Verification --}}
                <div class="lg:col-span-2">
                    <!-- Companies Need Verification -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">
                            <span class="text-yellow-600">Perusahaan Menunggu Verifikasi</span>
                            <span class="inline-block ml-2 px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-bold rounded-full">{{ $pendingCompanies }}</span>
                        </h4>

                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @forelse($companiesNeedVerification as $company)
                                <div class="p-4 border border-yellow-100 rounded-lg bg-yellow-50 hover:bg-yellow-100 transition duration-150">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">{{ $company->user->name }}</p>
                                            <p class="text-sm text-gray-600 truncate">{{ $company->user->email }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Terdaftar: {{ $company->created_at->format('d M Y') }}</p>
                                        </div>
                                        <a href="{{ route('admin.companies.show', $company) }}" class="px-3 py-1 text-xs font-semibold bg-yellow-500 text-white rounded hover:bg-yellow-600 transition whitespace-nowrap">
                                            Lihat
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500">
                                    <p class="text-sm">Semua perusahaan sudah terverifikasi ✓</p>
                                </div>
                            @endforelse
                        </div>

                        @if($pendingCompanies > 5)
                            <a href="{{ route('admin.companies.index') }}" class="text-sm font-semibold text-yellow-600 hover:text-yellow-800 mt-4 block text-center">
                                Lihat Semua Verifikasi →
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
