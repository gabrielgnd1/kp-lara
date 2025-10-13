<div class="flex min-h-screen items-center justify-center bg-gray-950 py-12">
    <div class="w-screen max-w-md">
        <div class="space-y-8 px-6 py-12 bg-gray-900 shadow-xl rounded-xl sm:px-12 sm:py-16">
            <h2 class="text-center text-2xl font-bold tracking-tight text-white">
                Log In to Your Account
            </h2>

            <form wire:submit.prevent="login" class="mt-8 space-y-6">
                <div class="space-y-4 -mt-4">
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-white">
                            Email
                        </label>
                        <div class="mt-2">
                            <input id="email" type="email" wire:model.defer="email" required
                                   class="block w-full rounded-lg border-0 py-2 px-3 bg-gray-800 text-white shadow-sm ring-1 ring-inset ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6"
                                   placeholder="you@email.com" />
                            @error('email') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-white">
                            Password
                        </label>
                        <div class="mt-2">
                            <input id="password" type="password" wire:model.defer="password" required
                                   class="block w-full rounded-lg border-0 py-2 px-3 bg-gray-800 text-white shadow-sm ring-1 ring-inset ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6"
                                   placeholder="••••••••" />
                            @error('password') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember" type="checkbox" wire:model.defer="remember"
                           class="h-4 w-4 rounded border-gray-700 bg-gray-800 text-amber-500 focus:ring-amber-500" />
                    <label for="remember" class="ml-3 block text-sm leading-6 text-white">
                        Remember me
                    </label>
                </div>

                <div>
                    <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-amber-500 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-amber-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500">
                        Log In
                    </button>
                </div>

                <div class="text-center text-sm text-gray-400">
                    © {{ date('Y') }} — UTC IOC / UPC
                </div>
            </form>
        </div>
    </div>
</div>
