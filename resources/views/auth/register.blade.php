<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - JobFindr</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s ease;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
</head>

<body class="bg-white dark:bg-gray-900 
             min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <a href="/" class="text-4xl font-extrabold text-gray-900 dark:text-white">
            Job<span class="text-indigo-600">Findr</span>
        </a>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow-xl shadow-gray-200/50 dark:shadow-none 
                    border border-gray-100 dark:border-gray-700 sm:rounded-xl sm:px-10">

            <h2 id="register-title" class="text-xl font-bold text-gray-900 dark:text-white mb-6 text-center">
                Daftar Akun Baru
            </h2>

            <!-- FORM -->
            <form method="POST" action="{{ $isCompany ? route('company-register') : route('register') }}"
                class="space-y-6">
                @csrf

                <!-- NAME -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $isCompany ? 'Nama Perusahaan' : 'Nama Lengkap' }}
                    </label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                        placeholder="{{ $isCompany ? 'Masukkan Nama Perusahaan Anda' : 'Masukkan Nama Anda' }}" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                dark:bg-gray-900 dark:text-white px-3 py-2 shadow-sm placeholder-gray-400
                                focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        autocomplete="username" placeholder="your.email@example.com" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                dark:bg-gray-900 dark:text-white px-3 py-2 shadow-sm placeholder-gray-400
                                focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password
                    </label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                        placeholder="••••••••" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                dark:bg-gray-900 dark:text-white px-3 py-2 shadow-sm placeholder-gray-400
                                focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Confirm -->
                <div class="mt-4">
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Konfirmasi Password
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        autocomplete="new-password" placeholder="••••••••" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                dark:bg-gray-900 dark:text-white px-3 py-2 shadow-sm placeholder-gray-400
                                focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- BUTTONS -->
                <div class="flex items-center justify-between pt-2">

                    <!-- Daftar sebagai perusahaan -->
                    <a href="{{ route('company-register') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 
                               border border-transparent rounded-lg font-semibold text-xs 
                               text-white dark:text-gray-800 uppercase tracking-widest 
                               hover:bg-gray-700 dark:hover:bg-white transition ease-in-out duration-150">
                        Daftar sebagai Perusahaan
                    </a>

                    <!-- Submit -->
                    <button type="submit" class="flex justify-center rounded-lg bg-indigo-600 py-2.5 px-4 text-sm font-semibold text-white 
                               shadow-md hover:bg-indigo-700 transition duration-150">
                        Daftar
                    </button>
                </div>

                <!-- Already registered -->
                <div class="text-center pt-4">
                    <a href="{{ route('login') }}"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                        Sudah punya akun? Masuk
                    </a>
                </div>

            </form>
        </div>
    </div>

</body>

</html>