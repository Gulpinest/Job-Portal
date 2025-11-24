<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="text-2xl font-extrabold text-gray-900 transition duration-200">
                        Job<span class="text-indigo-600">Findr</span>
                    </a>
                </div>

                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="font-medium text-sm rounded-lg px-3 py-2 text-gray-900 hover:bg-gray-100 transition duration-150">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @auth
                        @if(auth()->user()->isAdmin())
                            {{-- Link Admin Dashboard --}}
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                                class="font-medium text-sm rounded-lg px-3 py-2 text-gray-900 hover:bg-gray-100 transition duration-150">
                                {{ __('Admin Panel') }}
                            </x-nav-link>

                        @elseif(auth()->user()->isPelamar())
                            {{-- Link Resume (Pelamar) --}}
                            <x-nav-link :href="route('resumes.index')" :active="request()->routeIs('resumes.*')"
                                class="font-medium text-sm rounded-lg px-3 py-2 text-gray-900 hover:bg-gray-100 transition duration-150">
                                {{ __('Resume') }}
                            </x-nav-link>
                            {{-- Link Lowongan (Pelamar) --}}
                            <x-nav-link :href="route('lowongans.pelamar_index')"
                                :active="request()->routeIs('lowongans.pelamar_index')"
                                class="font-medium text-sm rounded-lg px-3 py-2 text-gray-900 hover:bg-gray-100 transition duration-150">
                                {{ __('Lowongan') }}
                            </x-nav-link>
                            {{-- Link Lamaran Saya (Pelamar) --}}
                            <x-nav-link :href="route('lowongans.lamaran_saya')"
                                :active="request()->routeIs('lowongans.lamaran_saya')"
                                class="font-medium text-sm rounded-lg px-3 py-2 text-gray-900 hover:bg-gray-100 transition duration-150 {{ request()->routeIs('lowongans.lamaran_saya') ? 'text-indigo-600 font-bold' : '' }}">
                                {{ __('Lamaran Saya') }}
                            </x-nav-link>

                        @elseif (auth()->user()->isCompany())
                            {{-- Link Lowongan (Perusahaan) --}}
                            <x-nav-link :href="route('lowongans.index')" :active="request()->routeIs('lowongans.index')"
                                class="font-medium text-sm rounded-lg px-3 py-2 text-gray-900 hover:bg-gray-100 transition duration-150">
                                {{ __('Lowongan') }}
                            </x-nav-link>

                            {{-- Link Jadwal Interview (Perusahaan) --}}
                            <x-nav-link :href="route('interview-schedules.index')"
                                :active="request()->routeIs('interview-schedules.*')"
                                class="font-medium text-sm rounded-lg px-3 py-2 text-gray-900 hover:bg-gray-100 transition duration-150">
                                {{ __('Jadwal Interview') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-semibold rounded-xl text-gray-700 bg-gray-100 hover:text-gray-900 hover:bg-gray-200 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Link Profil (Dropdown Item) --}}
                        @if(auth()->user()->isPelamar() && isset(auth()->user()->pelamar))
                            <x-dropdown-link :href="route('pelamar.profil')" class="text-gray-900 hover:bg-gray-100">
                                {{ __('Profile Pelamar') }}
                            </x-dropdown-link>
                        @elseif(auth()->user()->isCompany())
                            <x-dropdown-link :href="route('profile.edit')" class="text-gray-900 hover:bg-gray-100">
                                {{ __('Profile Perusahaan') }}
                            </x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('profile.edit')" class="text-gray-900 hover:bg-gray-100">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                            this.closest('form').submit();" class="text-gray-900 hover:bg-gray-100">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-800 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-900 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="text-gray-900 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'text-indigo-600 font-bold' : '' }}">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @auth
                @if(auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                        class="text-gray-900 hover:bg-gray-100 {{ request()->routeIs('admin.*') ? 'text-indigo-600 font-bold' : '' }}">
                        {{ __('Admin Panel') }}
                    </x-responsive-nav-link>

                @elseif(auth()->user()->isPelamar())
                    <x-responsive-nav-link :href="route('resumes.index')" :active="request()->routeIs('resumes.*')"
                        class="text-gray-900 hover:bg-gray-100 {{ request()->routeIs('resumes.*') ? 'text-indigo-600 font-bold' : '' }}">
                        {{ __('Resume') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('lowongans.pelamar_index')"
                        :active="request()->routeIs('lowongans.pelamar_index')"
                        class="text-gray-900 hover:bg-gray-100 {{ request()->routeIs('lowongans.pelamar_index') ? 'text-indigo-600 font-bold' : '' }}">
                        {{ __('Lowongan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('lowongans.lamaran_saya')"
                        :active="request()->routeIs('lowongans.lamaran_saya')"
                        class="text-gray-900 hover:bg-gray-100 {{ request()->routeIs('lowongans.lamaran_saya') ? 'text-indigo-600 font-bold' : '' }}">
                        {{ __('Lamaran Saya') }}
                    </x-responsive-nav-link>
                @elseif (auth()->user()->isCompany())
                    <x-responsive-nav-link :href="route('lowongans.index')" :active="request()->routeIs('lowongans.index')"
                        class="text-gray-900 hover:bg-gray-100 {{ request()->routeIs('lowongans.index') ? 'text-indigo-600 font-bold' : '' }}">
                        {{ __('Lowongan') }}
                    </x-responsive-nav-link>

                    {{-- Link Jadwal Interview (Mobile) --}}
                    <x-responsive-nav-link :href="route('interview-schedules.index')"
                        :active="request()->routeIs('interview-schedules.*')"
                        class="text-gray-900 hover:bg-gray-100 {{ request()->routeIs('interview-schedules.*') ? 'text-indigo-600 font-bold' : '' }}">
                        {{ __('Jadwal Interview') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-900">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-700">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                {{-- Link Profil Mobile --}}
                @if(auth()->user()->isPelamar() && isset(auth()->user()->pelamar))
                    <x-responsive-nav-link :href="route('pelamar.profil')" class="text-gray-900 hover:bg-gray-100">
                        {{ __('Profile Pelamar') }}
                    </x-responsive-nav-link>
                @elseif(auth()->user()->isCompany())
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-900 hover:bg-gray-100">
                        {{ __('Profile Perusahaan') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-900 hover:bg-gray-100">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-gray-900 hover:bg-gray-100">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
