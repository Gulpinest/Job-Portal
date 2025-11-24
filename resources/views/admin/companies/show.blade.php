@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.companies.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                ← Kembali ke Daftar Perusahaan
            </a>
        </div>

        <!-- Company Header Card -->
        <div class="bg-white rounded-lg shadow p-8 mb-6">
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
                    <p class="text-gray-900 mt-1">{{ $company->user->email }}</p>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-600">Nomor Telepon</label>
                    <p class="text-gray-900 mt-1">{{ $company->no_telp_perusahaan ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-600">Alamat</label>
                    <p class="text-gray-900 mt-1">{{ $company->alamat ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-bold text-gray-600">Website</label>
                    <p class="text-gray-900 mt-1">
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
                    <p class="text-gray-900 mt-1">{{ $company->deskripsi ?? '-' }}</p>
                </div>
            </div>

            <!-- Account Status Info -->
            <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-6">
                <h3 class="font-bold text-blue-900 mb-2">Informasi Akun</h3>
                <p class="text-blue-800 text-sm">
                    <strong>Nama User:</strong> {{ $company->user->name }}<br>
                    <strong>Peran:</strong> {{ $company->user->role->nama_role ?? 'N/A' }}<br>
                    <strong>Status Akun:</strong> {{ $company->user->is_active ? 'Aktif' : 'Nonaktif' }}
                </p>
            </div>

            <!-- Action Buttons -->
            @if(!$company->is_verified)
                <div class="flex gap-4">
                    <!-- Verify Button -->
                    <form action="{{ route('admin.companies.verify', $company) }}" method="POST" class="flex-1">
                        @csrf
                        @method('POST')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memverifikasi perusahaan ini?')" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition">
                            ✓ Verifikasi Perusahaan
                        </button>
                    </form>

                    <!-- Reject Button (with modal) -->
                    <button onclick="showRejectModal()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition">
                        ✕ Tolak Perusahaan
                    </button>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded p-4 text-center">
                    <p class="text-green-800 font-bold">Perusahaan ini telah diverifikasi dan dapat memposting lowongan.</p>
                </div>
            @endif
        </div>

        <!-- Jobs Posted -->
        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Lowongan yang Diposting</h2>
            @if($company->lowongans->count() > 0)
                <div class="space-y-4">
                    @foreach($company->lowongans as $lowongan)
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <h3 class="font-bold text-gray-900">{{ $lowongan->nama_lowongan }}</h3>
                            <p class="text-gray-600 text-sm mt-1">{{ $lowongan->deskripsi_lowongan }}</p>
                            <div class="mt-3 flex justify-between items-center text-sm text-gray-600">
                                <span>{{ $lowongan->lamarans->count() }} lamaran</span>
                                <span>Diposting: {{ $lowongan->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-6">Belum ada lowongan yang diposting.</p>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md mx-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Tolak Perusahaan</h2>
        <p class="text-gray-600 mb-6">
            Akun perusahaan dan pengguna terkait akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
        </p>
        
        <div class="flex gap-4">
            <button onclick="hideRejectModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-bold py-2 px-4 rounded">
                Batal
            </button>
            <form action="{{ route('admin.companies.reject', $company) }}" method="POST" class="flex-1">
                @csrf
                @method('POST')
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Ya, Tolak
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    
    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection
