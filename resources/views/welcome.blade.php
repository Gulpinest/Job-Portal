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

            <!-- Search Bar -->
            <div
                class="p-4 sm:p-6 bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-3 w-full">
                    <input type="text" placeholder="Job Title, Keywords, or Company"
                        class="flex-grow px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-xl placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-white dark:bg-gray-900 transition duration-150" />
                    <input type="text" placeholder="Location (City, State, or Remote)"
                        class="flex-grow px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-xl placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-white dark:bg-gray-900 transition duration-150" />
                    <button
                        class="px-8 py-4 font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition duration-150 shadow-lg shadow-indigo-500/50">
                        Search Jobs
                    </button>
                </div>
            </div>
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
                <a href="#"
                    class="p-6 flex flex-col items-center text-center bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02] border border-gray-200 dark:border-gray-700">
                    <svg class="category-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Design & Creative</span>
                    <span class="text-sm font-semibold text-red-500 mt-1">(653)</span>
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