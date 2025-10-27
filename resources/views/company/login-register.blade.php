<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Company Login/Register') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p>Welcome to the Company Login/Register page.</p>

                    <!-- Button for logging in as a company -->
                    <a href="{{ route('company.register') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Register as Company
                    </a>

                    <!-- Button for logging in as a company -->
                    <a href="{{ route('company.login') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold text-sm rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Log in as Company
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
