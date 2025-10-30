
<x-filament-panels::page>
    <div class="max-w-2xl mx-auto py-8">
        <form wire:submit.prevent="submit" class="space-y-6">
            {{ $this->form }}
            <div class="pt-2">
                <button type="submit" class="flex w-full justify-center rounded-lg bg-[#A8DE30] px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#A8DE30]/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#A8DE30] transition-colors" style="background-color: #A8DE30 !important; color: white !important;">
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
