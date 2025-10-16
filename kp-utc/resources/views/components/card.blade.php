@props([
    'title' => null,
    'footer' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm border border-[#A8DE30]/20']) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-[#A8DE30]/10">
            <h3 class="text-lg font-medium text-[#31312C]">{{ $title }}</h3>
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 border-t border-[#A8DE30]/10 bg-gray-50">
            {{ $footer }}
        </div>
    @endif
</div>