<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-gray-600 text-sm font-medium">Total Pengguna</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
                            </div>
                            <div class="text-4xl text-blue-500 opacity-20">👥</div>
                        </div>
                    </div>
                </div>

                <!-- Total Pelamars -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-gray-600 text-sm font-medium">Total Pelamar</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $pelamars }}</p>
                            </div>
                            <div class="text-4xl text-green-500 opacity-20">👨‍💼</div>
                        </div>
                    </div>
                </div>

                <!-- Total Companies -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-gray-600 text-sm font-medium">Total Perusahaan</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $companies }}</p>
                            </div>
                            <div class="text-4xl text-purple-500 opacity-20">🏢</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('admin.users') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="px-6 py-6 text-center">
                        <div class="text-3xl mb-2">👤</div>
                        <p class="text-lg font-semibold text-gray-800">Kelola Pengguna</p>
                        <p class="text-sm text-gray-600 mt-1">Lihat dan kelola semua pengguna</p>
                    </div>
                </a>

                <a href="{{ route('admin.logs') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="px-6 py-6 text-center">
                        <div class="text-3xl mb-2">📋</div>
                        <p class="text-lg font-semibold text-gray-800">Activity Logs</p>
                        <p class="text-sm text-gray-600 mt-1">Lihat aktivitas pengguna</p>
                    </div>
                </a>

                <a href="#" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="px-6 py-6 text-center">
                        <div class="text-3xl mb-2">📊</div>
                        <p class="text-lg font-semibold text-gray-800">Laporan</p>
                        <p class="text-sm text-gray-600 mt-1">Lihat laporan sistem</p>
                    </div>
                </a>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentLogs as $log)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $log->user?->name ?? 'Unknown User' }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $log->aksi }}</p>
                                </div>
                                <p class="text-sm text-gray-500">
                                    {{ $log->created_at?->diffForHumans() ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <p class="text-gray-600">Belum ada aktivitas</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
