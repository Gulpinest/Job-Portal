<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Login') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <!-- Email Input -->
                        <div class="mb-4">
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 
                                       text-black bg-white dark:text-black dark:bg-white">
                        </div>

                        <!-- Password Input -->
                        <div class="mb-4">
                            <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" name="password" id="password" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 
                                       text-black bg-white dark:text-black dark:bg-white">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                            class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white dark:active:bg-gray-300">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
