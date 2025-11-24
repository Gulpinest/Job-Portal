<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Skill Saya') }}
            </h2>
            <a href="{{ route('skills.create') }}"
                class="inline-flex items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Skill
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg">
                    <div class="font-semibold">✓ Berhasil</div>
                    <p class="text-sm mt-1">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Grid Skill Cards --}}
            @if (count($skills) > 0)
                <div class="space-y-4">
                    @foreach ($skills as $skill)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="flex items-center justify-between p-6">
                                {{-- Left: Skill Info --}}
                                <div class="flex-1 flex items-center">
                                    {{-- Skill Name & Level --}}
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900">
                                            {{ $skill->nama_skill }}
                                        </h3>
                                        <div class="flex items-center mt-2 space-x-3">
                                            {{-- Level Badge --}}
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold
                                                @if($skill->pivot->level == 'Beginner')
                                                    bg-blue-100 text-blue-800
                                                @elseif($skill->pivot->level == 'Intermediate')
                                                    bg-yellow-100 text-yellow-800
                                                @elseif($skill->pivot->level == 'Advanced')
                                                    bg-orange-100 text-orange-800
                                                @else
                                                    bg-red-100 text-red-800
                                                @endif
                                            ">
                                                {{ $skill->pivot->level }}
                                            </span>
                                            {{-- Experience --}}
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.488 5.951 1.488a1 1 0 001.169-1.409l-7-14z"></path>
                                                </svg>
                                                <span class="font-medium">{{ $skill->pivot->years_experience ?? 0 }} tahun</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right: Action Buttons --}}
                                <div class="flex gap-2 ml-4">
                                    <a href="{{ route('skills.edit', $skill->id_skill) }}"
                                        class="inline-flex justify-center items-center px-4 py-2.5 bg-amber-500 text-white text-sm font-semibold rounded-lg hover:bg-amber-600 transition shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('skills.destroy', $skill->id_skill) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus skill ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex justify-center items-center px-4 py-2.5 bg-red-500 text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition shadow-sm">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-12">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada skill</h3>
                        <p class="text-gray-600 mb-6">Mulai dengan menambahkan skill untuk profil Anda agar lebih menarik bagi perusahaan.</p>
                        <a href="{{ route('skills.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Skill Pertama
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
