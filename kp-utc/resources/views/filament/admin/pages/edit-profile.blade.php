
<x-filament-panels::page>
    <div class="max-w-2xl mx-auto py-8">
        <form wire:submit.prevent="submit" class="space-y-6">
            {{ $this->form }}
            <div class="pt-2">
                <button type="submit" class="filament-button filament-button-primary w-full">
                    <x-heroicon-o-check class="w-5 h-5 inline-block mr-1" />
                    Update Profile
                </button>
            </div>
        </form>
        @if (session('success'))
            <div class="mt-6 text-green-600 text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif
    </div>
</x-filament-panels::page>
