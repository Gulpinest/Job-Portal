<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <!-- Menggunakan font Inter sesuai tema JobFindr -->
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <!-- Jika Anda menggunakan Laravel Breeze/Jetstream, Tailwind CSS akan otomatis dimuat di sini -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styling kustom untuk memastikan font Inter digunakan di seluruh body -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Menggunakan warna primary indigo untuk elemen fokus (sesuai contoh landing page) */
        input:focus,
        button:focus,
        a:focus,
        select:focus,
        textarea:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.5);
            /* Indigo-600 */
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Menyertakan Navbar yang sudah disesuaikan -->
        @include('layouts.navigation')

        <!-- Page Heading (Jika ada) -->
        @if (isset($header))
            <header class="bg-white shadow-sm border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </div>
        </main>

        <!-- Footer Sederhana (sesuai tema landing page) -->
        <footer class="w-full mt-12 py-6 text-center text-xs text-gray-500 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                &copy; {{ date('Y') }} JobFindr. All rights reserved.
            </div>
        </footer>
    </div>
</body>

</html>
