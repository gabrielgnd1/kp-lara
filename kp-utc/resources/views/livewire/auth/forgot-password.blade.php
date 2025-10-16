<div>
    <form wire:submit="sendResetLink" class="space-y-6">
        <div>
            <div class="flex justify-between">
                <label for="email" class="block text-sm font-medium leading-6 text-[#31312C]">
                    Email address
                </label>
            </div>
            <div class="mt-2">
                <input id="email" type="email" wire:model="email" autocomplete="email" required
                       class="block w-full rounded-md border-0 py-1.5 px-3 text-[#31312C] shadow-sm ring-1 ring-inset ring-[#A8DE30]/40 focus:ring-2 focus:ring-inset focus:ring-[#A8DE30] placeholder:text-[#31312C]/60 transition-colors" />
                @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div>
            <button type="submit"
                    class="flex w-full justify-center rounded-md bg-[#A8DE30] px-3 py-1.5 text-sm font-semibold leading-6 text-[#31312C] shadow-sm hover:bg-[#A8DE30]/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#A8DE30] transition-colors">
                Send Password Reset Link
            </button>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="font-medium text-[#493852] hover:text-[#493852]/80 transition-colors">
                Back to login
            </a>
        </div>
    </form>
</div>