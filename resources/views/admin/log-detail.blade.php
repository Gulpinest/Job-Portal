<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a 
                    href="{{ route('admin.logs') }}" 
                    class="text-blue-600 hover:text-blue-800 font-medium"
                >
                    ← Kembali ke Log
                </a>
            </div>

            <!-- Log Detail Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Aktivitas</h3>
                </div>

                <div class="px-6 py-6 space-y-4">
                    <!-- ID -->
                    <div class="border-b border-gray-200 pb-4">
                        <p class="text-sm font-medium text-gray-600">ID Log</p>
                        <p class="text-lg text-gray-800">{{ $log->id_log }}</p>
                    </div>

                    <!-- Pengguna -->
                    <div class="border-b border-gray-200 pb-4">
                        <p class="text-sm font-medium text-gray-600">Pengguna</p>
                        <p class="text-lg text-gray-800">
                            {{ $log->user?->name ?? 'Unknown User' }}
                            <span class="text-sm text-gray-600">({{ $log->user?->email ?? 'N/A' }})</span>
                        </p>
                    </div>

                    <!-- Aktivitas -->
                    <div class="border-b border-gray-200 pb-4">
                        <p class="text-sm font-medium text-gray-600">Aktivitas</p>
                        <p class="text-lg text-gray-800">{{ $log->aksi }}</p>
                    </div>

                    <!-- Waktu Dibuat -->
                    <div class="border-b border-gray-200 pb-4">
                        <p class="text-sm font-medium text-gray-600">Waktu</p>
                        <p class="text-lg text-gray-800">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $log->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Updated At (if exists) -->
                    @if($log->updated_at != $log->created_at)
                        <div class="border-b border-gray-200 pb-4">
                            <p class="text-sm font-medium text-gray-600">Terakhir Diperbarui</p>
                            <p class="text-lg text-gray-800">
                                {{ $log->updated_at->format('d M Y H:i:s') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
