<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="px-6 py-4">
                    <form method="GET" action="{{ route('admin.logs') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Cari aktivitas..." 
                                value="{{ request('search') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                        <select 
                            name="user_id" 
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Semua Pengguna</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        >
                            Cari
                        </button>
                        <a 
                            href="{{ route('admin.logs') }}" 
                            class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition"
                        >
                            Reset
                        </a>
                    </form>

                    <div class="mt-4">
                        <form 
                            method="POST" 
                            action="{{ route('admin.logs.clear') }}"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua log?');"
                            class="inline"
                        >
                            @csrf
                            <button 
                                type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm"
                            >
                                Hapus Semua Log
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Logs Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Pengguna</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Aktivitas</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Waktu</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $log->user?->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ Str::limit($log->aksi, 50) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span title="{{ $log->created_at->format('d M Y H:i:s') }}">
                                            {{ $log->created_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a 
                                            href="{{ route('admin.logs.detail', $log) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium"
                                        >
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-600">
                                        Tidak ada log ditemukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
