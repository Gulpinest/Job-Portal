<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - JobFindr</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'indigo-600': '#4F46E5',
                        'indigo-700': '#4338ca',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <a href="/" class="text-4xl font-extrabold text-gray-900 dark:text-white">
            Job<span class="text-indigo-600">Findr</span>
        </a>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow-xl shadow-gray-200/50 dark:shadow-none 
                    border border-gray-100 dark:border-gray-700 sm:rounded-xl sm:px-10">

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username" placeholder="your.email@example.com" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 
                               dark:bg-gray-900 dark:text-white px-3 py-2 shadow-sm placeholder-gray-400
                               focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password
                    </label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        placeholder="••••••••" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 
                               dark:bg-gray-900 dark:text-white px-3 py-2 shadow-sm placeholder-gray-400
                               focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember + Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 
                                   dark:bg-gray-900 dark:border-gray-600">

                        <span class="ml-2 text-sm text-gray-900 dark:text-gray-300">
                            Remember me
                        </span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="flex w-full justify-center rounded-lg bg-indigo-600 py-2.5 px-4 text-sm font-semibold text-white 
                           shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Log in
                </button>
            </form>
        </div>

        <div class="mt-6 text-center">
            <a href="/"
                class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                &larr; Back to Landing Page
            </a>
        </div>
    </div>

</body>

</html>