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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

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

                {{-- Card 4: Admin Actions --}}
                <div class="bg-indigo-50 rounded-2xl p-6 shadow-md border border-indigo-200">
                    <p class="text-sm font-medium text-indigo-700">Fitur Admin</p>
                    <p class="text-3xl font-extrabold text-indigo-800 mt-1">3</p>
                </div>
            </div>

            <!-- 3. MAIN CONTENT: Quick Actions & Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left Column: Quick Actions --}}
                <div class="lg:col-span-1 space-y-6">

                    <!-- Quick Action Buttons -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 space-y-3">
                        <h4 class="text-lg font-bold text-gray-900 border-b pb-3">Aksi Cepat Admin</h4>
                        <a href="{{ route('admin.skills.index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-indigo-700 transition shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Kelola Skill Master
                        </a>
                        <a href="{{ route('admin.companies.index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-blue-700 transition shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Verifikasi Company
                        </a>
                        <a href="{{ route('admin.users') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 hover:bg-gray-200 transition shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Kelola Pengguna
                        </a>
                    </div>

                    <!-- System Status -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 space-y-4">
                        <h4 class="text-lg font-bold text-gray-900 border-b pb-3">Status Sistem</h4>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                <span class="text-gray-700 font-medium">Database</span>
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Online</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                <span class="text-gray-700 font-medium">Aplikasi</span>
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Running</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                <span class="text-gray-700 font-medium">Server</span>
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Recent Activities --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">Aktivitas Terbaru Sistem</h4>

                        <div class="space-y-3">
                            @forelse($recentLogs as $log)
                                <div class="p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition duration-150">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">
                                                {{ $log->user?->name ?? 'System' }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">{{ $log->aksi }}</p>
                                        </div>
                                        <span class="text-xs font-medium text-gray-500">
                                            {{ $log->created_at?->diffForHumans() ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500">
                                    <p>Belum ada aktivitas tercatat</p>
                                </div>
                            @endforelse
                        </div>

                        <a href="{{ route('admin.logs') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 mt-5 block text-center">
                            Lihat Semua Aktivitas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
