<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Edit Profil') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- User Account Information --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Company Information (if company user) --}}
            @if ($user->isCompany() && $company)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-company-information-form')
                    </div>
                </div>
            @endif

            {{-- Change Password --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
