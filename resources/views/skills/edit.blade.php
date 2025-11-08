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
                    <div class="mb-4">
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

                    {{-- Nama Skill --}}
                    <div class="mb-4">
                        <x-input-label for="nama_skill" :value="__('Nama Skill')" />
                        <x-text-input id="nama_skill" name="nama_skill" type="text"
                            class="mt-1 block w-full"
                            value="{{ old('nama_skill', $skill->nama_skill) }}"
                            placeholder="Contoh: Laravel, Public Speaking"
                            required />
                    </div>

                    <div class="flex items-center justify-end mt-6">
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
