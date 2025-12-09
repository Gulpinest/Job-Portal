<x-app-layout>
    <style>
        .package-card {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .package-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .active-badge {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }
    </style>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-3xl p-8 shadow-xl border border-gray-100">

                <header class="text-center mb-12">
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Pilih Paket Langganan</h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-4">
                        Tingkatkan paket Anda untuk membuka lebih banyak fitur dan limit lowongan yang lebih tinggi.
                    </p>
                    @if($company->package && $company->isSubscriptionActive())
                        <div class="inline-block bg-green-50 border border-green-200 rounded-lg px-6 py-3 mt-4">
                            <p class="text-sm text-green-700">
                                <strong>📦 Paket Aktif:</strong> {{ $company->package->nama_package }}
                            </p>
                            <p class="text-xs text-green-600 mt-2">
                                <strong>⏱️ Sisa Durasi:</strong> {{ $company->getRemainingDurationMonths() }} Bulan
                            </p>
                            @if($company->subscription_expired_at)
                                <p class="text-xs text-green-600 mt-1">
                                    <strong>📅 Berlaku hingga:</strong> {{ $company->subscription_expired_at->format('d M Y') }}
                                    <span class="ml-2 font-bold">({{ now()->diffInDays($company->subscription_expired_at) }}
                                        hari lagi)</span>
                                </p>
                            @endif
                            <p class="text-xs text-green-600 mt-2 font-semibold">
                                💡 Pilih paket di bawah untuk perpanjang atau upgrade langganan Anda
                            </p>
                        </div>
                    @elseif($company->package && !$company->isSubscriptionActive())
                        <div class="inline-block bg-amber-50 border border-amber-200 rounded-lg px-6 py-3 mt-4">
                            <p class="text-sm text-amber-700">
                                <strong>📦 Paket Terakhir:</strong> {{ $company->package->nama_package }}
                            </p>
                            @if($company->subscription_expired_at)
                                <p class="text-xs text-amber-600 mt-1">
                                    <strong>❌ Berakhir:</strong> {{ $company->subscription_expired_at->format('d M Y') }}
                                    <span class="ml-2 font-bold text-red-600">(Expired)</span>
                                </p>
                            @endif
                            <p class="text-xs text-amber-600 mt-2 font-semibold">
                                💡 Perpanjang langganan Anda sekarang untuk melanjutkan akses penuh
                            </p>
                        </div>
                    @endif
                </header>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">

                    <!-- KARTU 1: PAKET GRATIS -->
                    <div
                        class="package-card bg-white p-8 rounded-2xl shadow-lg flex flex-col justify-between border border-gray-200 relative overflow-hidden">
                        @if($company->package_id == 1)
                            <div
                                class="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold uppercase py-1 px-3 rounded-bl-lg active-badge">
                                Paket Aktif
                            </div>
                        @endif
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Paket Gratis</h2>
                            <p class="text-sm text-gray-500 mb-6 h-10">Cocok untuk startup yang baru memulai rekrutmen.
                            </p>

                            <div class="mb-8">
                                <p class="text-5xl font-extrabold text-gray-900">
                                    Gratis
                                </p>
                                <p class="text-sm font-medium text-gray-400 mt-2">Selamanya</p>
                            </div>

                            <ul class="space-y-4 text-gray-700 text-sm">
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span><strong class="font-semibold text-gray-900">2</strong> Lowongan Aktif
                                        Maksimal</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Akses Dashboard Dasar</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Kelola Lamaran Masuk</span>
                                </li>
                                <li class="flex items-start opacity-50">
                                    <span class="text-red-400 mr-3 font-bold text-lg">✕</span>
                                    <span class="line-through">Lowongan Tak Terbatas</span>
                                </li>
                                <li class="flex items-start opacity-50">
                                    <span class="text-red-400 mr-3 font-bold text-lg">✕</span>
                                    <span class="line-through">Laporan Analitik Mendalam</span>
                                </li>
                            </ul>
                        </div>
                        <form action="" class="mt-8">
                            <button type="" disabled
                                class="w-full bg-gray-200 text-white font-bold py-3 px-6 rounded-xl text-sm uppercase tracking-wide">
                                Pilih Paket Gratis
                            </button>
                        </form>
                    </div>

                    <!-- KARTU 2: PREMIUM 6 BULAN -->
                    <div
                        class="package-card bg-white p-8 rounded-2xl shadow-xl flex flex-col justify-between border-2 border-amber-500 transform md:scale-105 relative z-10">
                        @if($company->package_id == 2)
                            <div
                                class="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold uppercase py-1 px-3 rounded-bl-lg active-badge">
                                Paket Aktif
                            </div>
                        @else
                            <div
                                class="absolute top-0 right-0 bg-amber-500 text-white text-xs font-bold uppercase py-1 px-3 rounded-bl-lg">
                                Paling Populer
                            </div>
                        @endif
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Premium 6 Bulan</h2>
                            <p class="text-sm text-gray-500 mb-6 h-10">Paket fleksibel untuk perusahaan yang berkembang.
                            </p>

                            <div class="mb-8 transition-all duration-300 ease-in-out">
                                <p class="text-5xl font-extrabold text-amber-500 flex items-baseline">
                                    <span class="text-sm align-top mr-1">Rp</span>
                                    <span>4.500.000</span>
                                </p>
                                <p class="text-sm font-medium text-gray-400 mt-2">
                                    / 6 bulan
                                    <span class="text-amber-500 font-bold text-xs block">Rp 750 ribu per bulan</span>
                                </p>
                            </div>

                            <ul class="space-y-4 text-gray-700 text-sm">
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span><strong class="font-semibold text-gray-900">Unlimited Lowongan
                                            Pekerjaan</strong></span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Dashboard Lengkap & Analitik</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Kelola Jadwal Wawancara</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Prioritas Support Email</span>
                                </li>
                            </ul>
                        </div>
                        <form action="{{ route('payments.confirm', 2) }}" method="GET" class="mt-8">
                            <button type="submit"
                                class="w-full bg-amber-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:bg-amber-600 hover:shadow-amber-500/30 transition duration-300 text-sm uppercase tracking-wide">
                                Pilih Premium 6 Bulan
                            </button>
                        </form>
                    </div>

                    <!-- KARTU 3: PREMIUM 12 BULAN -->
                    <div
                        class="package-card bg-white p-8 rounded-2xl shadow-lg flex flex-col justify-between border border-gray-200 relative overflow-hidden">
                        @if($company->package_id == 3)
                            <div
                                class="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold uppercase py-1 px-3 rounded-bl-lg active-badge">
                                Paket Aktif
                            </div>
                        @endif
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Premium 12 Bulan</h2>
                            <p class="text-sm text-gray-500 mb-6 h-10">Komitmen penuh untuk hasil maksimal dalam
                                rekrutmen.</p>

                            <div class="mb-8 transition-all duration-300 ease-in-out">
                                <p class="text-5xl font-extrabold text-indigo-600 flex items-baseline">
                                    <span class="text-sm align-top mr-1">Rp</span>
                                    <span>8.000.000</span>
                                </p>
                                <p class="text-sm font-medium text-gray-400 mt-2">
                                    / 12 bulan
                                    <span class="text-indigo-500 font-bold text-xs block">Rp 667 ribu per bulan</span>
                                </p>
                            </div>

                            <ul class="space-y-4 text-gray-700 text-sm">
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span><strong class="font-semibold text-gray-900">Unlimited Lowongan
                                            Pekerjaan</strong></span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Semua Fitur Premium</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Hemat hingga 11% vs 6 Bulan</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Support Prioritas 24/7</span>
                                </li>
                            </ul>
                        </div>
                        <form action="{{ route('payments.confirm', 3) }}" method="GET" class="mt-8">
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-indigo-700 hover:shadow-indigo-500/30 transition duration-300 text-sm uppercase tracking-wide">
                                Pilih Premium 12 Bulan
                            </button>
                        </form>
                    </div>

                </div>

                <div class="text-center mt-12 pt-8 border-t border-gray-100">
                    <p class="text-gray-400 text-xs">
                        *Semua harga dalam Rupiah. Jika Anda membeli paket premium saat sudah memiliki langganan aktif,
                        masa berlaku langganan akan diperpanjang.
                        <br>Untuk bantuan lebih lanjut, silakan hubungi tim support kami.
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
