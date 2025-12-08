<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg shadow-xl p-8 border border-gray-100">

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Konfirmasi Pembayaran</h1>
                    <p class="text-gray-600">Pastikan detail di bawah sudah benar sebelum melanjutkan pembayaran</p>
                </div>

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Kesalahan Pembayaran
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc space-y-1 ml-5">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                {{ session('error') }}
                            </h3>
                        </div>
                    </div>
                </div>
                @endif

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Paket</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-blue-200">
                            <span class="text-gray-700">Paket:</span>
                            <span class="font-semibold text-gray-900">{{ $package->nama_package }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-blue-200">
                            <span class="text-gray-700">Perusahaan:</span>
                            <span class="font-semibold text-gray-900">{{ $company->nama_perusahaan }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-blue-200">
                            <span class="text-gray-700">Email:</span>
                            <span class="font-semibold text-gray-900">{{ Auth::user()->email }}</span>
                        </div>

                        @if($package->duration_months)
                        <div class="flex justify-between items-center pb-3 border-b border-blue-200">
                            <span class="text-gray-700">Durasi:</span>
                            <span class="font-semibold text-gray-900">{{ $package->duration_months }} Bulan</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center pt-3 border-t-2 border-blue-300 mt-4">
                            <span class="text-xl font-bold text-gray-900">Total Pembayaran:</span>
                            <span class="text-3xl font-bold text-blue-600">
                                Rp {{ number_format($package->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($package->job_limit)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
                    <p class="text-sm text-green-700">
                        <strong>✓ Limit Lowongan:</strong> {{ $package->job_limit }} lowongan aktif maksimal
                    </p>
                </div>
                @else
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
                    <p class="text-sm text-green-700">
                        <strong>✓ Limit Lowongan:</strong> Tidak terbatas
                    </p>
                </div>
                @endif

                <div class="flex gap-4">
                    <a href="{{ route('payments.packages') }}"
                        class="flex-1 text-center bg-gray-200 text-gray-700 font-bold py-3 px-4 rounded-lg hover:bg-gray-300 transition">
                        Kembali
                    </a>
                    
                    <form action="{{ route('payments.process', $package) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition">
                            Lanjutkan Pembayaran
                        </button>
                    </form>
                </div>

                <p class="text-center text-gray-500 text-xs mt-6">
                    Anda akan dialihkan ke halaman pembayaran virtual account setelah klik tombol di atas
                </p>

            </div>
        </div>
    </div>
</x-app-layout>
