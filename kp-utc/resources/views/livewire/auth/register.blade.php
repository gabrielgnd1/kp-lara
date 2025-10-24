@section('title', 'Create a new account')

<div class="flex min-h-screen items-center justify-center py-12">
    <div class="w-screen max-w-md">
        <div class="space-y-8 px-6 py-12 bg-white shadow-xl rounded-xl sm:px-12 sm:py-16 border border-[#A8DE30]/20">
            <div class="text-center">
                <img src="{{ asset('images/ioc_utc_upc.png') }}" alt="Logo" class="h-16 w-auto mx-auto">
                <h2 class="mt-6 text-2xl font-bold tracking-tight text-[#31312C]">
                    Create a new account
                </h2>
                <p class="mt-2 text-sm text-[#493852]/80">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-[#493852] hover:text-[#493852]/80 transition-colors">
                        Sign in
                    </a>
                </p>
            </div>

            <form wire:submit.prevent="register" class="mt-8 space-y-6">
                <div class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-[#31312C]">
                            Username
                        </label>
                        <div class="mt-2">
                            <input wire:model.lazy="username" id="username" type="text" required autofocus
                                   class="block w-full rounded-lg border border-[#A8DE30]/20 py-2 px-3 bg-white text-[#31312C] shadow-sm ring-0 placeholder:text-gray-400 focus:border-[#A8DE30] focus:ring-2 focus:ring-[#A8DE30]/20 transition-colors sm:text-sm sm:leading-6" />
                            @error('username')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-[#31312C]">
                            Full Name
                        </label>
                        <div class="mt-2">
                            <input wire:model.lazy="name" id="name" type="text" required 
                                   class="block w-full rounded-lg border border-[#A8DE30]/20 py-2 px-3 bg-white text-[#31312C] shadow-sm ring-0 placeholder:text-gray-400 focus:border-[#A8DE30] focus:ring-2 focus:ring-[#A8DE30]/20 transition-colors sm:text-sm sm:leading-6" />
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-[#31312C]">
                            Email
                        </label>
                        <div class="mt-2">
                            <input wire:model.lazy="email" id="email" type="email" required
                                   class="block w-full rounded-lg border border-[#A8DE30]/20 py-2 px-3 bg-white text-[#31312C] shadow-sm ring-0 placeholder:text-gray-400 focus:border-[#A8DE30] focus:ring-2 focus:ring-[#A8DE30]/20 transition-colors sm:text-sm sm:leading-6" />
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-[#31312C]">
                            Password
                        </label>
                        <div class="mt-2">
                            <input wire:model.lazy="password" id="password" type="password" required
                                   class="block w-full rounded-lg border border-[#A8DE30]/20 py-2 px-3 bg-white text-[#31312C] shadow-sm ring-0 placeholder:text-gray-400 focus:border-[#A8DE30] focus:ring-2 focus:ring-[#A8DE30]/20 transition-colors sm:text-sm sm:leading-6" />
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-[#31312C]">
                            Confirm Password
                        </label>
                        <div class="mt-2">
                            <input wire:model.lazy="password_confirmation" id="password_confirmation" type="password" required
                                   class="block w-full rounded-lg border border-[#A8DE30]/20 py-2 px-3 bg-white text-[#31312C] shadow-sm ring-0 placeholder:text-gray-400 focus:border-[#A8DE30] focus:ring-2 focus:ring-[#A8DE30]/20 transition-colors sm:text-sm sm:leading-6" />
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-[#A8DE30] px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#A8DE30]/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#A8DE30] transition-colors">
                        Create Account
                    </button>
                </div>

                <div class="text-center text-sm text-[#31312C]/40">
                    By registering, you agree to our Terms of Service and Privacy Policy.
                </div>
            </form>
        </div>
    </div>
</div>
