<div class="flex min-h-screen items-center justify-center py-12">
    <div class="w-screen max-w-md">
        <div class="space-y-8 px-6 py-12 bg-white shadow-xl rounded-xl sm:px-12 sm:py-16 border border-[#A8DE30]/20">
            <div class="text-center">
                <img src="{{ asset('images/ioc_utc_upc.png') }}" alt="Logo" class="h-16 w-auto mx-auto">
                <h2 class="mt-6 text-2xl font-bold tracking-tight text-[#31312C]">
                    Welcome Back
                </h2>
                <p class="mt-2 text-sm text-[#493852]/80">
                    Sign in to access your account
                </p>
            </div>

            <form wire:submit.prevent="login" class="mt-8 space-y-6">
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-[#31312C]">
                            Email
                        </label>
                        <div class="mt-2">
                            <input id="email" type="email" wire:model.defer="email" required
                                   class="block w-full rounded-lg border border-[#A8DE30]/20 py-2 px-3 bg-white text-[#31312C] shadow-sm ring-0 placeholder:text-gray-400 focus:border-[#A8DE30] focus:ring-2 focus:ring-[#A8DE30]/20 transition-colors sm:text-sm sm:leading-6"
                                   placeholder="you@email.com" />
                            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-[#31312C]">
                            Password
                        </label>
                        <div class="mt-2">
                            <input id="password" type="password" wire:model.defer="password" required
                                   class="block w-full rounded-lg border border-[#A8DE30]/20 py-2 px-3 bg-white text-[#31312C] shadow-sm ring-0 placeholder:text-gray-400 focus:border-[#A8DE30] focus:ring-2 focus:ring-[#A8DE30]/20 transition-colors sm:text-sm sm:leading-6"
                                   placeholder="••••••••" />
                            @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" wire:model.defer="remember"
                               class="h-4 w-4 rounded border-[#A8DE30]/40 text-[#A8DE30] focus:ring-[#A8DE30]/20 transition-colors" />
                        <label for="remember" class="ml-3 block text-sm leading-6 text-[#31312C]">
                            Remember me
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-[#A8DE30] px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#A8DE30]/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#A8DE30] transition-colors">
                        Sign In
                    </button>
                </div>

                <div class="text-center">
                    <span class="text-sm text-[#31312C]/60">
                        Don't have an account?
                    </span>
                    <a href="{{ route('register') }}" class="ml-1 text-sm font-medium text-[#493852] hover:text-[#493852]/80 transition-colors">
                        Sign up
                    </a>
                </div>

                <div class="text-center text-sm text-[#31312C]/40">
                    © {{ date('Y') }} — Unit Training Center Petrokimia Gresik
                </div>
            </form>
        </div>
    </div>
</div>
