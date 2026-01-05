<div class="flex h-screen w-screen items-start justify-center pt-32 overflow-hidden fixed inset-0 bg-gray-50">
    <div class="w-screen max-w-md">
        <div class="space-y-4 px-5 py-6 bg-white shadow-xl rounded-xl sm:px-8 sm:py-8 border border-[#A8DE30]/20">
            <div class="text-center">
                <img src="{{ asset('images/ioc_utc_upc.png') }}" alt="Logo" class="h-14 w-auto mx-auto">
                <h2 class="mt-4 text-xl font-bold tracking-tight text-[#31312C]">
                    Welcome Back
                </h2>
                <p class="mt-1 text-xs text-[#493852]/80">
                    Sign in to access your account
                </p>
            </div>

            <form wire:submit.prevent="login" class="mt-6 space-y-4">
                <div class="space-y-3">
                    <div>
                        <label for="email" class="block text-xs font-medium leading-5 text-[#31312C]">
                            Email
                        </label>
                        <div class="mt-1">
                            <input id="email" type="email" wire:model.defer="email" required
                                   class="block w-full rounded-lg border border-[#A8DE30]/20 py-2 px-3 bg-white text-xs text-[#31312C] shadow-sm ring-0 placeholder:text-gray-400 focus:border-[#A8DE30] focus:ring-2 focus:ring-[#A8DE30]/20 transition-colors"
                                   placeholder="you@email.com" />
                            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-medium leading-5 text-[#31312C]">
                            Password
                        </label>
                        <div class="mt-1">
                            <input id="password" type="password" wire:model.defer="password" required
                                   class="block w-full rounded-lg border border-[#A8DE30]/20 py-2 px-3 bg-white text-xs text-[#31312C] shadow-sm ring-0 placeholder:text-gray-400 focus:border-[#A8DE30] focus:ring-2 focus:ring-[#A8DE30]/20 transition-colors"
                                   placeholder="••••••••" />
                            @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember" type="checkbox" wire:model.defer="remember"
                           class="h-3 w-3 rounded border-[#A8DE30]/40 text-[#A8DE30] focus:ring-[#A8DE30]/20 transition-colors" />
                    <label for="remember" class="ml-2 block text-xs leading-5 text-[#31312C]">
                            Remember me
                        </label>
                </div>

                <div>
                    <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-[#A8DE30] px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#A8DE30]/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#A8DE30] transition-colors">
                        Sign In
                    </button>
                </div>

                <div class="text-center text-xs text-[#31312C]/40">
                    © {{ date('Y') }} — UBAYA Training Center
                </div>
            </form>
        </div>
    </div>
</div>
