@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Skill Master</h1>

            <form method="POST" action="{{ route('admin.skills.update', $skill) }}">
                @csrf
                @method('PUT')

                <!-- Nama Skill -->
                <div class="mb-6">
                    <label for="nama_skill" class="block text-sm font-medium text-gray-900 mb-2">Nama Skill <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_skill" name="nama_skill" value="{{ old('nama_skill', $skill->nama_skill) }}"
                           class="w-full px-4 py-3 border @error('nama_skill') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('nama_skill')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-900 mb-2">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('deskripsi', $skill->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition">
                        Update Skill
                    </button>
                    <a href="{{ route('admin.skills.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-bold py-3 px-6 rounded-lg transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
