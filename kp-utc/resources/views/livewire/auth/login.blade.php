<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="max-w-md w-full bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-center text-2xl font-bold text-gray-700">Login</h2>

        <form wire:submit.prevent="login" class="mt-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model.defer="email" required
                       class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" />
                @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" wire:model.defer="password" required
                       class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" />
                @error('password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input id="remember" type="checkbox" wire:model="remember"
                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                    Login
                </button>
            </div>
        </form>
    </div>
</div>
