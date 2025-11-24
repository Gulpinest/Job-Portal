<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Skill') }}
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

                {{-- Info Message --}}
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg">
                    <p class="text-blue-800 dark:text-blue-200 text-sm">
                        Pilih skill dari daftar yang tersedia. Administrator dapat menambahkan skill baru.
                    </p>
                </div>

                <form action="{{ route('skills.store') }}" method="POST">
                    @csrf

                    {{-- Available Skills --}}
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                            Pilih Skill Anda <span class="text-red-600">*</span>
                        </label>

                        <div class="space-y-3">
                            @forelse($allSkills as $skill)
                                <div class="flex items-start p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="skill_{{ $skill->id_skill }}"
                                               name="skills[{{ $skill->id_skill }}][id_skill]"
                                               value="{{ $skill->id_skill }}"
                                               class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               @if(in_array($skill->id_skill, $selectedSkillIds ?? []))
                                                   checked
                                               @endif
                                               onchange="toggleSkillDetails({{ $skill->id_skill }})">
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <label for="skill_{{ $skill->id_skill }}" class="block font-medium text-gray-900 dark:text-gray-100 cursor-pointer">
                                            {{ $skill->nama_skill }}
                                        </label>
                                        @if($skill->deskripsi)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                {{ $skill->deskripsi }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Skill Details (Level & Experience) --}}
                                <div id="details_{{ $skill->id_skill }}" class="ml-8 mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hidden">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- Level --}}
                                        <div>
                                            <label for="level_{{ $skill->id_skill }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Level Keahlian
                                            </label>
                                            <select id="level_{{ $skill->id_skill }}"
                                                    name="skills[{{ $skill->id_skill }}][level]"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="Beginner">Pemula (Beginner)</option>
                                                <option value="Intermediate" selected>Menengah (Intermediate)</option>
                                                <option value="Advanced">Lanjut (Advanced)</option>
                                                <option value="Expert">Ahli (Expert)</option>
                                            </select>
                                        </div>

                                        {{-- Years of Experience --}}
                                        <div>
                                            <label for="experience_{{ $skill->id_skill }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Tahun Pengalaman
                                            </label>
                                            <input type="number"
                                                   id="experience_{{ $skill->id_skill }}"
                                                   name="skills[{{ $skill->id_skill }}][years_experience]"
                                                   min="0" max="70"
                                                   value="0"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                    <p>Belum ada skill yang tersedia. Hubungi administrator.</p>
                                </div>
                            @endforelse
                        </div>

                        <x-input-error :messages="$errors->get('skills')" class="mt-4" />
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex items-center justify-end mt-8 border-t pt-6">
                        <a href="{{ route('skills.index') }}"
                            class="mr-3 inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md text-sm hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Skill') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function toggleSkillDetails(skillId) {
            const checkbox = document.getElementById('skill_' + skillId);
            const details = document.getElementById('details_' + skillId);

            if (checkbox.checked) {
                details.classList.remove('hidden');
            } else {
                details.classList.add('hidden');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            @foreach($selectedSkillIds ?? [] as $skillId)
                const details = document.getElementById('details_{{ $skillId }}');
                if (details) {
                    details.classList.remove('hidden');
                }
            @endforeach
        });
    </script>
</x-app-layout>
