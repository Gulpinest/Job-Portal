<!-- Chosen Palette: Indigo & Amber on Warm Gray (Professional, Trustworthy, with Clear Highlights) -->
<!-- Application Structure Plan: The layout is designed as a centralized interactive pricing dashboard. 1. Header: Sets the context. 2. Control Panel: A toggle switch allowing users to switch between Monthly and Yearly billing, immediately revealing savings (Interaction). 3. Pricing Grid: Three cards displaying tiered value, dynamically updating prices based on the toggle state. 4. Footer: Essential disclaimers. This structure minimizes cognitive load by allowing direct comparison and immediate feedback on cost savings. -->
<!-- Visualization & Content Choices: Pricing Cards -> Goal: Compare & Inform -> Method: Grid Layout with Interactive Cards -> Interaction: Hover lift effects & Billing Cycle Toggle -> Justification: The toggle allows users to customize the view to their budget planning (Monthly vs Annual) without reloading the page. Unicode icons are used for instant rendering without external dependencies. -> Library: Vanilla JS + Tailwind. -->
<!-- CONFIRMATION: NO SVG graphics used. NO Mermaid JS used. -->

<x-app-layout>
    
    {{-- TAMBAHKAN BLOK INI AGAR PESAN ERROR MUNCUL --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Gagal!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Ada Kesalahan Input:</strong>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    {{-- BATAS AKHIR TAMBAHAN --}}

    <style>
        .package-card {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .package-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .toggle-checkbox:checked {
            right: 0;
            border-color: #4f46e5;
        }

        .toggle-checkbox:checked+.toggle-label {
            background-color: #4f46e5;
        }

        .toggle-checkbox:checked+.toggle-label:before {
            transform: translateX(100%);
        }
    </style>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-3xl p-8 shadow-xl border border-gray-100">

                <header class="text-center mb-12">
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Pilih Paket Tahunan yang Tepat
                        untuk Perusahaan Anda</h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                        Harga di bawah adalah tarif tahunan, memungkinkan Anda hemat hingga <span
                            class="font-bold text-amber-500">17%</span> dibandingkan pembayaran bulanan.
                    </p>
                    <!-- Tombol Toggle telah dihapus, tampilan ini sekarang statis Tahunan -->
                </header>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">

                    <!-- KARTU 1: PAKET DASAR (GRATIS) -->
                    <div
                        class="package-card bg-white p-8 rounded-2xl shadow-lg flex flex-col justify-between border border-gray-200 relative overflow-hidden">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Paket Dasar</h2>
                            <p class="text-sm text-gray-500 mb-6 h-10">Cocok untuk startup dan kebutuhan rekrutmen
                                sesekali.</p>

                            <div class="mb-8">
                                <p class="text-5xl font-extrabold text-indigo-600">
                                    Gratis
                                </p>
                                <p class="text-sm font-medium text-gray-400 mt-2">Selamanya</p>
                            </div>

                            <ul class="space-y-4 text-gray-700 text-sm">
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span><strong class="font-semibold text-gray-900">1</strong> Lowongan Aktif
                                        (Maksimal)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Dukungan Email Dasar</span>
                                </li>
                                <li class="flex items-start opacity-50">
                                    <span class="text-red-400 mr-3 font-bold text-lg">✕</span>
                                    <span class="line-through">Laporan Analitik</span>
                                </li>
                                <li class="flex items-start opacity-50">
                                    <span class="text-red-400 mr-3 font-bold text-lg">✕</span>
                                    <span class="line-through">Fitur Promosi Lowongan</span>
                                </li>
                            </ul>
                        </div>
                        <button disabled
                            class="mt-8 w-full bg-gray-100 text-gray-400 font-bold py-3 px-6 rounded-xl cursor-not-allowed text-sm uppercase tracking-wide">
                            Paket Saat Ini
                        </button>
                    </div>

                    <!-- KARTU 2: PAKET STANDAR (TAHUNAN) -->
                    <div
                        class="package-card bg-white p-8 rounded-2xl shadow-xl flex flex-col justify-between border-2 border-amber-500 transform md:scale-105 relative z-10">
                        <div
                            class="absolute top-0 right-0 bg-amber-500 text-white text-xs font-bold uppercase py-1 px-3 rounded-bl-lg">
                            Paling Populer
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Paket Standar</h2>
                            <p class="text-sm text-gray-500 mb-6 h-10">Pilihan terbaik untuk perusahaan dengan rekrutmen
                                berkelanjutan.</p>

                            <div class="mb-8 transition-all duration-300 ease-in-out">
                                <p class="text-5xl font-extrabold text-amber-500 flex items-baseline">
                                    <span class="text-sm align-top mr-1">Rp</span>
                                    <span id="price-standard">4.990.000</span>
                                </p>
                                <!-- Harga Tahunan (Diskon Sudah Dihitung) -->
                                <p class="text-sm font-medium text-gray-400 mt-2" id="period-standard">
                                    / tahun
                                    <span class="text-amber-500 font-bold text-xs block">Hemat Rp 998.000</span>
                                </p>
                            </div>

                            <ul class="space-y-4 text-gray-700 text-sm">
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span><strong class="font-semibold text-gray-900">10</strong> Lowongan Aktif
                                        (Maksimal)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Laporan Analitik Dasar</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Dukungan Chat & Email Prioritas</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Akses ke Database Kandidat</span>
                                </li>
                            </ul>
                        </div>
                        <form action="{{ route('payment.subscribe') }}" method="POST">
                            @csrf
                            <input type="hidden" name="package_id" value="2">
                            <button type="submit" class="mt-8 w-full block text-center bg-amber-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:bg-amber-600 hover:shadow-amber-500/30 transition duration-300 text-sm uppercase tracking-wide">
                                Pilih Paket Standar
                            </button>
                        </form>
                    </div>

                    <!-- KARTU 3: PAKET PREMIUM (TAHUNAN) -->
                    <div
                        class="package-card bg-white p-8 rounded-2xl shadow-lg flex flex-col justify-between border border-gray-200 relative overflow-hidden">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Paket Premium</h2>
                            <p class="text-sm text-gray-500 mb-6 h-10">Solusi lengkap untuk perusahaan besar dengan
                                volume tinggi.</p>

                            <div class="mb-8 transition-all duration-300 ease-in-out">
                                <p class="text-5xl font-extrabold text-indigo-600 flex items-baseline">
                                    <span class="text-sm align-top mr-1">Rp</span>
                                    <span id="price-premium">9.990.000</span>
                                </p>
                                <!-- Harga Tahunan (Diskon Sudah Dihitung) -->
                                <p class="text-sm font-medium text-gray-400 mt-2" id="period-premium">
                                    / tahun
                                    <span class="text-indigo-500 font-bold text-xs block">Hemat Rp 1.998.000</span>
                                </p>
                            </div>

                            <ul class="space-y-4 text-gray-700 text-sm">
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span><strong class="font-semibold text-gray-900">Lowongan Tak
                                            Terbatas</strong></span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Laporan Analitik Mendalam</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span>Konsultan Akun Dedikasi</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2 font-bold text-lg">✓</span>
                                    <span><strong class="font-semibold text-gray-900">2</strong> Lowongan Dipromosikan /
                                        bln</span>
                                </li>
                            </ul>
                        </div>
                        <form action="{{ route('payment.subscribe') }}" method="POST">
                            @csrf
                            <input type="hidden" name="package_id" value="3">
                            <button type="submit" class="mt-8 w-full block text-center bg-indigo-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-indigo-700 hover:shadow-indigo-500/30 transition duration-300 text-sm uppercase tracking-wide">
                                Pilih Paket Premium
                            </button>
                        </form>
                    </div>

                </div>

                <div class="text-center mt-12 pt-8 border-t border-gray-100">
                    <p class="text-gray-400 text-xs">
                        *Harga belum termasuk PPN. Semua paket dapat dibatalkan kapan saja.
                        <br>Untuk kebutuhan korporasi khusus (Enterprise), silakan hubungi tim penjualan kami.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('billing-toggle');
            const monthlyLabel = document.getElementById('monthly-label');
            const yearlyLabel = document.getElementById('yearly-label');

            const data = {
                standard: {
                    monthly: '499.000',
                    yearly: '4.990.000',
                    savings: 'Hemat Rp 998.000'
                },
                premium: {
                    monthly: '999.000',
                    yearly: '9.990.000',
                    savings: 'Hemat Rp 1.998.000'
                }
            };

            const els = {
                standard: {
                    price: document.getElementById('price-standard'),
                    period: document.getElementById('period-standard')
                },
                premium: {
                    price: document.getElementById('price-premium'),
                    period: document.getElementById('period-premium')
                }
            };

            function updatePrices(isYearly) {
                const type = isYearly ? 'yearly' : 'monthly';
                const periodText = isYearly ? '/ tahun' : '/ bulan';

                els.standard.price.innerText = data.standard[type];
                els.standard.period.innerHTML = isYearly ? periodText + ' <span class="text-amber-500 font-bold text-xs block">' + data.standard.savings + '</span>' : periodText;

                els.premium.price.innerText = data.premium[type];
                els.premium.period.innerHTML = isYearly ? periodText + ' <span class="text-indigo-500 font-bold text-xs block">' + data.premium.savings + '</span>' : periodText;

                if (isYearly) {
                    yearlyLabel.classList.add('text-indigo-600');
                    monthlyLabel.classList.remove('text-indigo-600');
                } else {
                    yearlyLabel.classList.remove('text-indigo-600');
                    monthlyLabel.classList.add('text-indigo-600');
                }
            }

            toggle.addEventListener('change', function () {
                updatePrices(this.checked);
            });
        });
    </script>
</x-app-layout>
```