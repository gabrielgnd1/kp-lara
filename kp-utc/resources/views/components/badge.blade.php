@props([
    'variant' => 'primary',
])

@php
$variants = [
    'primary' => 'bg-[#A8DE30]/10 text-[#A8DE30]',
    'secondary' => 'bg-[#493852]/10 text-[#493852]',
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'danger' => 'bg-red-100 text-red-800',
];

$classes = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>