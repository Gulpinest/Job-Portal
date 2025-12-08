<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg shadow-xl p-8 border border-gray-100">

                <div class="text-center mb-8">
                    <div class="text-6xl mb-4 animate-bounce">✅</div>
                    <h1 class="text-3xl font-bold text-green-600 mb-4">Paket Gratis Diaktifkan!</h1>
                    <p class="text-gray-600">Anda sekarang dapat memulai posting lowongan</p>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Paket</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-green-200">
                            <span class="text-gray-700">Paket:</span>
                            <span class="font-semibold text-gray-900">{{ $company->package->nama_package }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-green-200">
                            <span class="text-gray-700">Perusahaan:</span>
                            <span class="font-semibold text-gray-900">{{ $company->nama_perusahaan }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-green-200">
                            <span class="text-gray-700">Biaya:</span>
                            <span class="font-semibold text-green-600">Gratis Selamanya</span>
                        </div>

                        <div class="flex justify-between items-center pt-3">
                            <span class="text-gray-700">Status:</span>
                            <span class="inline-block bg-green-200 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                ✓ Aktif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                    <p class="text-sm text-blue-700">
                        <strong>📋 Limit Lowongan:</strong> Anda dapat membuat hingga <strong>2 lowongan</strong> aktif secara bersamaan
                    </p>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-8">
                    <p class="text-sm text-amber-700">
                        <strong>💡 Tip:</strong> Upgrade ke paket Premium kapan saja untuk mendapatkan lowongan tak terbatas dan fitur tambahan lainnya
                    </p>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('company.dashboard') }}"
                        class="flex-1 text-center bg-gray-200 text-gray-700 font-bold py-3 px-4 rounded-lg hover:bg-gray-300 transition">
                        Kembali ke Dashboard
                    </a>
                    
                    <a href="{{ route('lowongans.index') }}"
                        class="flex-1 text-center bg-green-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-700 transition">
                        Mulai Posting Lowongan
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
