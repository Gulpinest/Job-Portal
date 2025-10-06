<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Company Registration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('company.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="company_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Company Name</label>
                            <input type="text" name="company_name" id="company_name" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 
                                       text-black bg-white dark:text-black dark:bg-white">
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Phone</label>
                            <input type="text" name="phone" id="phone" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 
                                       text-black bg-white dark:text-black dark:bg-white">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 
                                       text-black bg-white dark:text-black dark:bg-white"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 
                                       text-black bg-white dark:text-black dark:bg-white">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" name="password" id="password" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 
                                       text-black bg-white dark:text-black dark:bg-white">
                        </div>

                        <div class="mb-6">
                            <label for="password_confirmation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 
                                       text-black bg-white dark:text-black dark:bg-white">
                        </div>

                        <button type="submit" 
                            class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white dark:active:bg-gray-300">
                            Register
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>