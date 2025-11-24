<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Tambah Skill Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">

                <h3 class="text-xl font-extrabold text-gray-900 mb-6 border-b pb-4">
                    Pilih Skill dari Daftar yang Tersedia
                </h3>

                {{-- Pesan Error --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg">
                        <div class="font-bold">{{ __('Whoops! Ada Kesalahan.') }}</div>
                        <ul class="mt-3 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Info Message --}}
                <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 shadow-sm rounded-r-lg">
                    <p class="text-sm font-medium">
                        💡 Pilih skill dari daftar yang tersedia dan tentukan level keahlian Anda. Administrator dapat menambahkan skill baru.
                    </p>
                </div>

                <form action="{{ route('skills.store') }}" method="POST">
                    @csrf

                    {{-- Available Skills --}}
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-4">
                            Pilih Skill Anda <span class="text-red-600 font-bold">*</span>
                        </label>

                        <div class="space-y-3 max-h-96 overflow-y-auto pr-4">
                            @forelse($allSkills as $skill)
                                <div class="p-4 border-2 border-gray-200 rounded-xl transition">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-6 mt-1">
                                            <input type="checkbox"
                                                   id="skill_{{ $skill->id_skill }}"
                                                   name="skills[{{ $skill->id_skill }}][id_skill]"
                                                   value="{{ $skill->id_skill }}"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer"
                                                   @if(in_array($skill->id_skill, $selectedSkillIds ?? []))
                                                       checked
                                                   @endif
                                                   onchange="toggleSkillDetails({{ $skill->id_skill }})">
                                        </div>
                                        <div class="ml-4 flex-1 cursor-pointer" onclick="toggleCheckbox({{ $skill->id_skill }})">
                                            <label for="skill_{{ $skill->id_skill }}" class="block font-semibold text-gray-900 cursor-pointer">
                                                {{ $skill->nama_skill }}
                                            </label>
                                            @if($skill->deskripsi)
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $skill->deskripsi }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Skill Details (Level & Experience) --}}
                                    <div id="details_{{ $skill->id_skill }}" class="mt-4 ml-10 p-4 bg-indigo-50 rounded-lg border border-indigo-200 hidden">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            {{-- Level --}}
                                            <div>
                                                <label for="level_{{ $skill->id_skill }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Level Keahlian
                                                </label>
                                                <select id="level_{{ $skill->id_skill }}"
                                                        name="skills[{{ $skill->id_skill }}][level]"
                                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="Beginner">🟢 Pemula (Beginner)</option>
                                                    <option value="Intermediate" selected>🟡 Menengah (Intermediate)</option>
                                                    <option value="Advanced">🟠 Lanjut (Advanced)</option>
                                                    <option value="Expert">🔴 Ahli (Expert)</option>
                                                </select>
                                            </div>

                                            {{-- Years of Experience --}}
                                            <div>
                                                <label for="experience_{{ $skill->id_skill }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Tahun Pengalaman
                                                </label>
                                                <input type="number"
                                                       id="experience_{{ $skill->id_skill }}"
                                                       name="skills[{{ $skill->id_skill }}][years_experience]"
                                                       min="0" max="70"
                                                       value="0"
                                                       class="w-full px-3 py-2 rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                                    <p class="font-medium">Belum ada skill yang tersedia</p>
                                    <p class="text-sm mt-1">Hubungi administrator untuk menambahkan skill baru.</p>
                                </div>
                            @endforelse
                        </div>

                        <x-input-error :messages="$errors->get('skills')" class="mt-4" />
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex items-center justify-end pt-6 border-t border-gray-100 space-x-3">
                        <a href="{{ route('skills.index') }}"
                            class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                            {{ __('Batal') }}
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                            {{ __('Simpan Skill') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function toggleCheckbox(skillId) {
            const checkbox = document.getElementById('skill_' + skillId);
            checkbox.checked = !checkbox.checked;
            toggleSkillDetails(skillId);
        }

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
