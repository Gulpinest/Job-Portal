<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Skill') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                {{-- Pesan Error --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                        <div class="font-medium text-red-600 dark:text-red-400">Terjadi kesalahan:</div>
                        <ul class="mt-2 text-sm text-red-600 dark:text-red-400 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('skills.update', $skill->id_skill) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Skill --}}
                        <div class="md:col-span-2">
                            <x-input-label for="nama_skill" :value="__('Nama Skill')" />
                            <x-text-input id="nama_skill" name="nama_skill" type="text"
                                class="mt-1 block w-full"
                                value="{{ old('nama_skill', $skill->nama_skill) }}"
                                placeholder="Contoh: Laravel, Public Speaking"
                                required />
                            <x-input-error :messages="$errors->get('nama_skill')" class="mt-2" />
                        </div>

                        {{-- Level Skill --}}
                        <div>
                            <x-input-label for="level" :value="__('Level Keahlian')" />
                            <select id="level" name="level" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih Level --</option>
                                <option value="Beginner" {{ old('level', $skill->level) == 'Beginner' ? 'selected' : '' }}>
                                    Pemula (Beginner)
                                </option>
                                <option value="Intermediate" {{ old('level', $skill->level) == 'Intermediate' ? 'selected' : '' }}>
                                    Menengah (Intermediate)
                                </option>
                                <option value="Advanced" {{ old('level', $skill->level) == 'Advanced' ? 'selected' : '' }}>
                                    Lanjut (Advanced)
                                </option>
                                <option value="Expert" {{ old('level', $skill->level) == 'Expert' ? 'selected' : '' }}>
                                    Ahli (Expert)
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('level')" class="mt-2" />
                        </div>

                        {{-- Years of Experience --}}
                        <div>
                            <x-input-label for="years_experience" :value="__('Tahun Pengalaman')" />
                            <x-text-input id="years_experience" name="years_experience" type="number"
                                class="mt-1 block w-full"
                                placeholder="0"
                                min="0" max="70"
                                value="{{ old('years_experience', $skill->years_experience ?? 0) }}" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Berapa tahun pengalaman Anda dengan skill ini?</p>
                            <x-input-error :messages="$errors->get('years_experience')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t pt-6">
                        <a href="{{ route('skills.index') }}"
                            class="mr-3 inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md text-sm hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Update Skill') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
