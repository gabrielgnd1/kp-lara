@props(['user' => null])

<nav class="bg-white border-b border-[#A8DE30]/10" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/ioc_utc_upc.png') }}" alt="Logo" class="h-8 w-auto">
                    </a>
                </div>

                <!-- Main Navigation -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    @auth
                        <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-[#A8DE30] text-[#31312C]' : 'border-transparent text-[#31312C] hover:text-[#A8DE30] hover:border-[#A8DE30]' }} transition-colors">
                            Dashboard
                        </a>
                        <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-[#31312C] hover:text-[#A8DE30] hover:border-[#A8DE30] transition-colors">
                            Edit Profile
                        </a>
                        <a href="{{ route('laporan.list') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('laporan.list') ? 'border-[#A8DE30] text-[#31312C]' : 'border-transparent text-[#31312C] hover:text-[#A8DE30] hover:border-[#A8DE30]' }} transition-colors">
                            Detail Reservasi
                        </a>
                        <a href="{{ route('filament.admin.resources.fasilitas.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-[#31312C] hover:text-[#A8DE30] hover:border-[#A8DE30] transition-colors">
                            Detail Fasilitas
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- Profile Dropdown -->
                    <div class="ml-3 relative" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" class="flex items-center text-[#31312C] hover:text-[#A8DE30] transition-colors">
                                <span class="mr-2">{{ Auth::user()->name }}</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-[#31312C] hover:bg-[#A8DE30]/5">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="space-x-4">
                    </div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-lg text-[#31312C] hover:text-[#A8DE30] hover:bg-[#A8DE30]/5 transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 {{ request()->routeIs('home') ? 'text-[#A8DE30] border-l-4 border-[#A8DE30] bg-[#A8DE30]/5' : 'text-[#31312C] hover:text-[#A8DE30] hover:bg-[#A8DE30]/5 hover:border-[#A8DE30] border-l-4 border-transparent' }}">
                    Dashboard
                </a>
                <a href="#" class="block pl-3 pr-4 py-2 text-[#31312C] hover:text-[#A8DE30] hover:bg-[#A8DE30]/5 hover:border-[#A8DE30] border-l-4 border-transparent">
                    Edit Profile
                </a>
                <a href="{{ route('laporan.list') }}" class="block pl-3 pr-4 py-2 {{ request()->routeIs('laporan.list') ? 'text-[#A8DE30] border-l-4 border-[#A8DE30] bg-[#A8DE30]/5' : 'text-[#31312C] hover:text-[#A8DE30] hover:bg-[#A8DE30]/5 hover:border-[#A8DE30] border-l-4 border-transparent' }}">
                    Detail Reservasi
                </a>
                @if(in_array((int)Auth::user()->id_role, [2, 3]))
                    <a href="{{ route('filament.admin.resources.fasilitas.index') }}" class="block pl-3 pr-4 py-2 text-[#31312C] hover:text-[#A8DE30] hover:bg-[#A8DE30]/5 hover:border-[#A8DE30] border-l-4 border-transparent">
                        Detail Fasilitas
                    </a>
                @endif
            @endauth
        </div>

        @auth
            <div class="pt-4 pb-3 border-t border-[#A8DE30]/10">
                <div class="flex items-center px-4">
                    <div class="ml-3">
                        <div class="text-base font-medium text-[#31312C]">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-[#31312C]/60">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-base text-[#31312C] hover:text-[#A8DE30] hover:bg-[#A8DE30]/5 transition-colors">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-[#A8DE30]/10 space-y-1">
                <a href="{{ route('login') }}" class="block px-4 py-2 text-base text-[#31312C] hover:text-[#A8DE30] hover:bg-[#A8DE30]/5 transition-colors">
                    Log in
                </a>
            </div>
        @endauth
    </div>
</nav>