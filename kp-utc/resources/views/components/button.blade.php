@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium transition-colors rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2';

$variants = [
    'primary' => 'bg-[#A8DE30] text-white hover:bg-[#A8DE30]/90 focus:ring-[#A8DE30]/20',
    'secondary' => 'bg-[#493852] text-white hover:bg-[#493852]/90 focus:ring-[#493852]/20',
    'outline' => 'border-2 border-[#A8DE30] text-[#A8DE30] hover:bg-[#A8DE30]/10 focus:ring-[#A8DE30]/20',
    'ghost' => 'text-[#31312C] hover:bg-[#31312C]/5 focus:ring-[#31312C]/20',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size] . ($disabled ? ' opacity-50 cursor-not-allowed' : '');
@endphp

<button
    {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}
    @if($disabled) disabled @endif
>
    {{ $slot }}
</button>