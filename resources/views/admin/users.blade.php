<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="px-6 py-4">
                    <form method="GET" action="{{ route('admin.users') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Cari nama atau email..." 
                                value="{{ request('search') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                        <select 
                            name="role_id" 
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Semua Role</option>
                            <option value="1" {{ request('role_id') == '1' ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ request('role_id') == '2' ? 'selected' : '' }}>Pelamar</option>
                            <option value="3" {{ request('role_id') == '3' ? 'selected' : '' }}>Perusahaan</option>
                        </select>
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        >
                            Cari
                        </button>
                        <a 
                            href="{{ route('admin.users') }}" 
                            class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition"
                        >
                            Reset
                        </a>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Nama</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Email</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Role</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Terdaftar</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $user->isAdmin() ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $user->isPelamar() ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $user->isCompany() ? 'bg-blue-100 text-blue-800' : '' }}
                                        ">
                                            {{ $user->getRoleName() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if(!$user->isAdmin() && auth()->id() !== $user->id)
                                            <form 
                                                method="POST" 
                                                action="{{ route('admin.users.delete', $user) }}"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit" 
                                                    class="text-red-600 hover:text-red-800 font-medium"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-600">
                                        Tidak ada pengguna ditemukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
