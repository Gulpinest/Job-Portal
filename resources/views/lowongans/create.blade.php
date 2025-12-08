<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Buat Lowongan Pekerjaan Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">

                <h3 class="text-xl font-extrabold text-gray-900 mb-6 border-b pb-4">
                    Detail Pekerjaan Baru
                </h3>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg">
                        <div class="font-bold">{{ __('Whoops! Ada Kesalahan.') }}</div>
                        <ul class="mt-3 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('lowongans.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-6">

                        <!-- BARIS 1: Judul & Posisi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Judul -->
                            <div>
                                <label for="judul" class="block font-medium text-sm text-gray-700">Judul
                                    Lowongan</label>
                                <input id="judul" name="judul" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('judul') }}" required
                                    placeholder="Contoh: Lowongan Digital Marketing Lead" />
                            </div>

                            <!-- Posisi -->
                            <div>
                                <label for="posisi" class="block font-medium text-sm text-gray-700">Posisi</label>
                                <input id="posisi" name="posisi" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('posisi') }}" required placeholder="Contoh: Digital Marketing Lead" />
                            </div>
                        </div>

                        <!-- BARIS 2: Lokasi & Gaji -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Lokasi Kantor/Remote -->
                            <div>
                                <label for="lokasi_kantor" class="block font-medium text-sm text-gray-700">Lokasi
                                    Kantor/Remote</label>
                                <input id="lokasi_kantor" name="lokasi_kantor" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('lokasi_kantor') }}" placeholder="Contoh: Jakarta Selatan, Remote" />
                            </div>

                            <!-- Gaji -->
                            <div>
                                <label for="gaji" class="block font-medium text-sm text-gray-700">Perkiraan Gaji (Per
                                    Bulan)</label>
                                <input id="gaji" name="gaji" type="text"
                                    class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('gaji') }}" placeholder="Contoh: Rp 8.000.000 - Rp 12.000.000" />
                            </div>
                        </div>

                        <!-- BARIS 3: Tipe Kerja -->
                        <div>
                            <label for="tipe_kerja" class="block font-medium text-sm text-gray-700">Tipe
                                Pekerjaan</label>
                            <select name="tipe_kerja" id="tipe_kerja" required
                                class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">
                                <option value="">-- Pilih Tipe Kerja --</option>
                                @php $tipeKerjaOptions = ['Full Time', 'Part Time', 'Remote', 'Freelance', 'Contract']; @endphp
                                @foreach ($tipeKerjaOptions as $tipe)
                                    <option value="{{ $tipe }}" {{ old('tipe_kerja') == $tipe ? 'selected' : '' }}>
                                        {{ $tipe }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- BARIS 4: Deskripsi Lowongan (WYSIWYG) -->
                        <div>
                            <label for="editor-deskripsi" class="block font-medium text-sm text-gray-700 mb-2">Deskripsi Pekerjaan <span class="text-red-500">*</span></label>
                            <div id="editor-deskripsi" class="bg-white border border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" style="height: 300px;"></div>
                            <textarea id="deskripsi" name="deskripsi" style="display: none;"></textarea>
                            <div id="deskripsi-error" class="text-red-500 text-xs mt-2 hidden"></div>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">Gunakan formatting untuk membuat deskripsi lebih menarik</p>
                        </div>

                        <!-- BARIS 5: Persyaratan Tambahan -->
                        <div>
                            <label for="persyaratan_tambahan" class="block font-medium text-sm text-gray-700 mb-2">Persyaratan Tambahan</label>
                            <textarea id="persyaratan_tambahan" name="persyaratan_tambahan"
                                class="block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                rows="4"
                                placeholder="Contoh:&#10;• Pengalaman minimal 3 tahun&#10;• Sertifikasi PMP&#10;• English proficiency yang baik">{{ old('persyaratan_tambahan') }}</textarea>
                            <p class="text-xs text-gray-500 mt-2">Gunakan bullet points untuk daftar persyaratan</p>
                        </div>

                        <!-- BARIS 6: STATUS (Status Open/Closed) -->
                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-700">Status Lowongan</label>
                            <select name="status" id="status" required
                                class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">
                                <option value="Open" {{ old('status') == 'Open' ? 'selected' : '' }}>Open (Menerima
                                    Lamaran)</option>
                                <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed (Ditutup)
                                </option>
                            </select>
                        </div>

                        <!-- Skill yang Dibutuhkan -->
                        <div class="mt-6 p-6 bg-gray-50 rounded-2xl border border-gray-200">
                            <label class="block font-bold text-sm text-gray-900 mb-4">
                                <svg class="w-4 h-4 inline mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.488 5.951 1.488a1 1 0 001.169-1.409l-7-14z"></path>
                                </svg>
                                Skill yang Dibutuhkan
                            </label>

                            @if($allSkills->count() > 0)
                                <!-- Search Input -->
                                <div class="mb-4">
                                    <input type="text" id="skillSearch" placeholder="Cari skill..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                                    />
                                </div>

                                <!-- Skills Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" id="skillsContainer">
                                    @foreach ($allSkills as $skill)
                                        <label class="flex items-center p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-indigo-50 transition skill-item" data-skill="{{ strtolower($skill->nama_skill) }}">
                                            <input type="checkbox" name="skills[]" value="{{ $skill->nama_skill }}"
                                                {{ in_array($skill->nama_skill, $selectedSkills) ? 'checked' : '' }}
                                                class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                                            <span class="ml-3 text-sm text-gray-700">{{ $skill->nama_skill }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <!-- No Results Message -->
                                <div id="noResults" class="hidden mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-sm text-yellow-800">Skill tidak ditemukan</p>
                                </div>

                                <p class="text-xs text-gray-500 mt-3">Pilih satu atau lebih skill yang diperlukan untuk posisi ini.</p>
                            @else
                                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                    <p class="text-sm text-amber-800">Belum ada skill master. <a href="{{ route('admin.skills.index') }}" class="font-semibold hover:underline">Buat skill master terlebih dahulu</a></p>
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('lowongans.index') }}"
                            class="px-6 py-3 bg-gray-200 text-gray-800 rounded-xl font-semibold hover:bg-gray-300 transition shadow-sm">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <i class="fas fa-plus mr-2"></i> {{ __('Simpan Lowongan') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
let quillEditor = null;

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Quill Editor untuk field deskripsi
    quillEditor = new Quill('#editor-deskripsi', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image'],
                [{ 'align': [] }],
            ]
        },
        placeholder: 'Jelaskan detail pekerjaan, tanggung jawab, dan deskripsi lengkap posisi ini...'
    });

    // Set initial value jika ada old value
    @if(old('deskripsi'))
        quillEditor.root.innerHTML = `{{ old('deskripsi') }}`;
    @endif

    // Sync Quill ke textarea saat ada perubahan
    quillEditor.on('text-change', function() {
        document.getElementById('deskripsi').value = quillEditor.root.innerHTML;
    });

    // Handle form submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Sync final content ke textarea
            document.getElementById('deskripsi').value = quillEditor.root.innerHTML;

            // Get text content untuk validasi
            const deskripsiText = quillEditor.getText().trim();
            const errorDiv = document.getElementById('deskripsi-error');

            // Validasi bahwa deskripsi tidak kosong
            if (!deskripsiText || deskripsiText.length === 0) {
                e.preventDefault();
                errorDiv.textContent = 'Deskripsi pekerjaan tidak boleh kosong!';
                errorDiv.classList.remove('hidden');
                return false;
            }

            // Clear error message jika valid
            errorDiv.classList.add('hidden');
        });
    }

    // Skill search functionality
    const searchInput = document.getElementById('skillSearch');
    const skillItems = document.querySelectorAll('.skill-item');
    const noResults = document.getElementById('noResults');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            skillItems.forEach(item => {
                const skillName = item.dataset.skill || item.textContent.toLowerCase();

                if (skillName.includes(searchTerm)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide no results message
            if (noResults) {
                noResults.style.display = visibleCount === 0 && searchTerm ? 'block' : 'none';
            }
        });
    }
});
</script>
