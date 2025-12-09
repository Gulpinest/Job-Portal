@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Skill') }}
    </h2>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Skill Master</h1>
                <p class="text-gray-600 mt-2">Kelola daftar skill yang tersedia untuk pelamar</p>
            </div>
            <a href="{{ route('admin.skills.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition">
                + Tambah Skill Baru
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('message') }}
            </div>
        @endif

        <!-- Skills Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($skills->count() > 0)
                <table class="min-w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Nama Skill</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Deskripsi</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Dibuat</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($skills as $skill)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $skill->id_skill }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $skill->nama_skill }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $skill->deskripsi ? Str::limit($skill->deskripsi, 50) : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $skill->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.skills.edit', $skill) }}" class="text-blue-600 hover:text-blue-900 font-medium text-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm" onclick="return confirm('Yakin ingin menghapus skill ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="bg-white px-6 py-4 border-t">
                    {{ $skills->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <p class="text-gray-500 text-lg">Belum ada skill master. <a href="{{ route('admin.skills.create') }}" class="text-indigo-600 hover:underline">Buat skill baru</a></p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
