<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobFindr - Discover Your Next Opportunity</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Font & Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
        }

        .category-icon {
            color: #4F46E5;
            width: 48px;
            height: 48px;
            margin-bottom: 12px;
            stroke-width: 1.5;
        }
    </style>

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'primary-indigo': '#4F46E5',
                        'indigo-600': '#4F46E5',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex flex-col items-center pt-6 pb-12">

    <!-- Navigation Bar -->
    <header class="w-full max-w-7xl px-4 sm:px-6 lg:px-8 mb-16">
        <nav class="flex justify-between items-center py-4 bg-transparent">
            <!-- Logo -->
            <a href="#" class="text-3xl font-extrabold text-gray-900 dark:text-white">
                Job<span class="text-indigo-600">Findr</span>
            </a>
            <!-- Authentication Buttons -->
            <div class="flex space-x-3 items-center">
                <a href="/login"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 border border-transparent rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition duration-200 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Log in
                </a>
                <a href="/register"
                    class="px-5 py-2 text-sm font-semibold bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 transition duration-200 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Sign Up
                </a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="w-full max-w-7xl flex flex-col items-center px-4 sm:px-6 lg:px-8">

        <!-- Hero Section -->
        <section class="text-center mb-20 max-w-4xl">
            <h1
                class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                Discover Your Next <span class="text-indigo-600 dark:text-indigo-400">Opportunity</span>
            </h1>
            <p class="text-lg sm:text-xl text-gray-500 dark:text-gray-400 mb-10 max-w-2xl mx-auto">
                Explore thousands of active job listings from top companies worldwide. Your dream career is just a
                search away.
            </p>
        </section>

        <!-- Stats Section -->
        <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 w-full max-w-5xl text-center mb-20">
            <!-- Card 1 -->
            <div
                class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-indigo-200 dark:border-indigo-900 transform hover:scale-[1.03] transition duration-300 ease-in-out">
                <div class="text-5xl font-extrabold text-indigo-600 dark:text-indigo-400 mb-2">500K+</div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">Active Listings</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">New opportunities posted daily across all
                    industries.</p>
            </div>
            <!-- Card 2 -->
            <div
                class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-indigo-200 dark:border-indigo-900 transform hover:scale-[1.03] transition duration-300 ease-in-out">
                <div class="text-5xl font-extrabold text-indigo-600 dark:text-indigo-400 mb-2">10K+</div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">Trusted Employers</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Vetted global and local companies hiring now.</p>
            </div>
            <!-- Card 3 -->
            <div
                class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-indigo-200 dark:border-indigo-900 transform hover:scale-[1.03] transition duration-300 ease-in-out">
                <div class="text-5xl font-extrabold text-indigo-600 dark:text-indigo-400 mb-2">1-Click</div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">Easy Apply</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Streamlined process to submit your application
                    quickly.</p>
            </div>
        </section>

        <!-- Browse Top Categories -->
        <section class="w-full max-w-7xl mb-24">
            <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white text-center mb-12">Browse Top Categories
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">

                <!-- Category Cards (contoh 1) -->
                <!-- 1. Design & Creative -->
                <a href="#"
                    class="p-6 flex flex-col items-center text-center bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02] border border-gray-200 dark:border-gray-700">
                    <!-- Icon for Design (Pencil/Edit) -->
                    <svg class="category-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Design & Creative</span>
                    <span class="text-sm font-semibold text-red-500 mt-1">(653)</span>
                </a>

                <!-- 2. Development & IT -->
                <a href="#"
                    class="p-6 flex flex-col items-center text-center bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02] border border-gray-200 dark:border-gray-700">
                    <!-- Icon for Code/Development (Brackets) -->
                    <svg class="category-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4">
                        </path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Development & IT</span>
                    <span class="text-sm font-semibold text-red-500 mt-1">(890)</span>
                </a>

                <!-- 3. Sales & Marketing -->
                <a href="#"
                    class="p-6 flex flex-col items-center text-center bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02] border border-gray-200 dark:border-gray-700">
                    <!-- Icon for Sales/Money (Target/Coin) -->
                    <svg class="category-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2v5m0-5c1.657 0 3-.895 3-2s-1.343-2-3-2zM21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Sales & Marketing</span>
                    <span class="text-sm font-semibold text-red-500 mt-1">(450)</span>
                </a>

                <!-- 4. Mobile Application -->
                <a href="#"
                    class="p-6 flex flex-col items-center text-center bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02] border border-gray-200 dark:border-gray-700">
                    <!-- Icon for Mobile (Phone) -->
                    <svg class="category-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Mobile Application</span>
                    <span class="text-sm font-semibold text-red-500 mt-1">(320)</span>
                </a>

                <!-- 5. Construction -->
                <a href="#"
                    class="p-6 flex flex-col items-center text-center bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02] border border-gray-200 dark:border-gray-700">
                    <!-- Icon for Construction (Helmet) -->
                    <svg class="category-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v14m12 0v-5m0 5a2 2 0 01-2 2H8a2 2 0 01-2-2v-3a2 2 0 00-2-2H4V8a2 2 0 012-2h4a2 2 0 012 2v2m0 10h4a2 2 0 002-2v-6a2 2 0 00-2-2h-4a2 2 0 00-2 2v6a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Construction</span>
                    <span class="text-sm font-semibold text-red-500 mt-1">(198)</span>
                </a>

                <!-- 6. Information Technology -->
                <a href="#"
                    class="p-6 flex flex-col items-center text-center bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02] border border-gray-200 dark:border-gray-700">
                    <!-- Icon for IT (Server/Database) -->
                    <svg class="category-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2">
                        </path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Information Technology</span>
                    <span class="text-sm font-semibold text-red-500 mt-1">(788)</span>
                </a>

                <!-- 7. Real Estate -->
                <a href="#"
                    class="p-6 flex flex-col items-center text-center bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02] border border-gray-200 dark:border-gray-700">
                    <!-- Icon for Real Estate (Building) -->
                    <svg class="category-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-6a2 2 0 012-2h2a2 2 0 012 2v6">
                        </path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Real Estate</span>
                    <span class="text-sm font-semibold text-red-500 mt-1">(210)</span>
                </a>

                <!-- 8. Content Writer -->
                <a href="#"
                    class="p-6 flex flex-col items-center text-center bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02] border border-gray-200 dark:border-gray-700">
                    <!-- Icon for Content Writer (Document/Paper) -->
                    <svg class="category-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m-6-8h6M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Content Writer</span>
                    <span class="text-sm font-semibold text-red-500 mt-1">(155)</span>
                </a>
                <!-- Category Cards lainnya bisa ditambahkan sama seperti template di atas -->
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-20 text-center text-xs text-gray-400 dark:text-gray-600">
        &copy; 2024 JobFindr. All rights reserved.
    </footer>

</body>

</html>