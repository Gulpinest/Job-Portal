<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ $company->nama_perusahaan }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.companies.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Daftar Perusahaan
                </a>
            </div>

            <!-- Company Header Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $company->nama_perusahaan }}</h1>
                    <p class="text-gray-600 mt-2">Industri: {{ $company->industri ?? '-' }}</p>
                </div>
                <div class="text-right">
                    @if($company->is_verified)
                        <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-bold">
                            ✓ Terverifikasi
                        </span>
                        <p class="text-gray-600 text-sm mt-2">
                            Diverifikasi pada: {{ $company->verified_at->format('d M Y H:i') }}
                        </p>
                    @else
                        <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-bold">
                            ⏳ Menunggu Verifikasi
                        </span>
                        <p class="text-gray-600 text-sm mt-2">
                            Mendaftar: {{ $company->created_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Company Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-t pt-6">
                <div>
                    <label class="text-sm font-bold text-gray-600">Email Perusahaan</label>
                    <p class="text-gray-900 mt-2 font-semibold">{{ $company->user->email }}</p>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-600">Nomor Telepon</label>
                    <p class="text-gray-900 mt-2 font-semibold">{{ $company->no_telp_perusahaan ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-600">Alamat</label>
                    <p class="text-gray-900 mt-2 font-semibold">{{ $company->alamat ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-600">Website</label>
                    <p class="text-gray-900 mt-2 font-semibold">
                        @if($company->website)
                            <a href="{{ $company->website }}" target="_blank" class="text-indigo-600 hover:underline">
                                {{ $company->website }}
                            </a>
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-gray-600">Deskripsi Perusahaan</label>
                    <p class="text-gray-900 mt-2 font-semibold">{{ $company->deskripsi ?? '-' }}</p>
                </div>
            </div>

            <!-- Account Status Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8">
                <h3 class="font-bold text-blue-900 mb-3">Informasi Akun</h3>
                <p class="text-blue-800 text-sm space-y-1">
                    <div><strong>Nama User:</strong> {{ $company->user->name }}</div>
                    <div><strong>Peran:</strong> {{ $company->user->role->nama_role ?? 'N/A' }}</div>
                    <div><strong>Status Akun:</strong> <span class="@if($company->user->is_active) text-green-600 @else text-red-600 @endif font-semibold">{{ $company->user->is_active ? 'Aktif' : 'Nonaktif' }}</span></div>
                </p>
            </div>

            <!-- Action Buttons -->
            @if(!$company->is_verified)
                <div class="flex gap-4 mb-8">
                    <!-- Verify Button -->
                    <form action="{{ route('admin.companies.verify', $company) }}" method="POST" class="flex-1">
                        @csrf
                        @method('POST')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memverifikasi perusahaan ini?')"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Verifikasi Perusahaan
                        </button>
                    </form>

                    <!-- Reject Button (with modal) -->
                    <button type="button" onclick="document.getElementById('rejectModal').showModal()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tolak Perusahaan
                    </button>
                </div>
            @else
                <div class="bg-green-50 border border-green-300 rounded-xl p-4 mb-8 text-center">
                    <p class="text-green-800 font-bold">✓ Perusahaan ini telah diverifikasi dan dapat memposting lowongan.</p>
                </div>
            @endif

            <!-- Jobs Posted -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Lowongan yang Diposting</h2>
                @if($company->lowongans->count() > 0)
                    <div class="space-y-3">
                        @foreach($company->lowongans as $lowongan)
                            <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition">
                                <h3 class="font-bold text-gray-900">{{ $lowongan->judul }}</h3>
                                <p class="text-gray-600 text-sm mt-2">{{ $lowongan->deskripsi }}</p>
                                <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
                                    <span class="font-semibold">{{ $lowongan->lamarans->count() }} lamaran</span>
                                    <span>Diposting: {{ $lowongan->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Belum ada lowongan yang diposting.</p>
                @endif
            </div>

        </div>
    </div>

    {{-- Reject Modal --}}
    <dialog id="rejectModal" class="backdrop:bg-black/50 rounded-2xl shadow-2xl max-w-md">
        <form action="{{ route('admin.companies.reject', $company) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('POST')
            <h2 class="text-xl font-bold text-gray-900">Tolak Perusahaan</h2>

            <p class="text-gray-600 text-sm">
                Akun perusahaan dan pengguna terkait akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
            </p>

            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('rejectModal').close()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                    Ya, Tolak
                </button>
            </div>
        </form>
    </dialog>

</x-app-layout>
