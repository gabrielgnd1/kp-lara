{{-- resources/views/livewire/auth/login.blade.php --}}
<div class="min-h-[100dvh] flex items-center justify-center px-4 bg-[#0B5D3B]">
    <div class="w-full max-w-sm bg-white/95 backdrop-blur-xl p-6 sm:p-8 rounded-2xl shadow-xl">
        {{-- Header --}}
        <div class="mb-6 text-center">
            {{-- Logo bisa ditaruh di sini --}}
            {{-- <img src="{{ asset('images/logo.png') }}" class="mx-auto h-12 mb-3" alt="Logo"> --}}
            <h2 class="text-2xl font-bold text-[#1F2937]">Login</h2>
            <p class="text-sm text-gray-500 mt-1">Masuk untuk melanjutkan</p>
        </div>

        {{-- Form --}}
        <form wire:submit.prevent="login" class="space-y-4">
            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" wire:model.defer="email" required
                       class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm 
                              focus:outline-none focus:ring-2 focus:ring-[#7BB542]"
                       placeholder="you@email.com" />
                @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" wire:model.defer="password" required
                       class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm 
                              focus:outline-none focus:ring-2 focus:ring-[#7BB542]"
                       placeholder="••••••••" />
                @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Remember me --}}
            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center gap-2 text-gray-700">
                    <input id="remember" type="checkbox" wire:model="remember"
                           class="rounded border-gray-300 focus:ring-2 focus:ring-[#7BB542]" />
                    Remember me
                </label>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-[#0B5D3B] hover:underline font-medium">Daftar</a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full flex justify-center py-3 px-4 text-sm font-semibold rounded-lg
                           text-white shadow-md transition
                           bg-[#7BB542] hover:bg-[#689a3b] focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                Login
            </button>
        </form>

        {{-- Footer --}}
        <p class="mt-6 text-center text-xs text-[#D4AF37]">
            © {{ now()->year }} — UTC IOC / UPC
        </p>
    </div>
</div>
