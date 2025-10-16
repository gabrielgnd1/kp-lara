@props([
    'heading',
    'subheading' => null,
    'actions' => null
])

<header class="mb-8 space-y-4">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#31312C]">
                {{ $heading }}
            </h1>

            @if ($subheading)
                <p class="mt-1 text-sm text-[#31312C]/60">
                    {{ $subheading }}
                </p>
            @endif
        </div>

        @if ($actions)
            <div class="flex items-center gap-4">
                {{ $actions }}
            </div>
        @endif
    </div>
</header>