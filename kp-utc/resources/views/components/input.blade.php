@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
])

<div>
    @if($label)
        <label class="block text-sm font-medium text-[#31312C]">
            {{ $label }}
        </label>
    @endif

    <div class="mt-1">
        <input 
            type="{{ $type }}" 
            {{ $attributes->merge([
                'class' => 'block w-full rounded-lg border border-[#A8DE30]/20 py-2 px-3 bg-white text-[#31312C] shadow-sm ring-0 placeholder:text-gray-400 focus:border-[#A8DE30] focus:ring-2 focus:ring-[#A8DE30]/20 transition-colors sm:text-sm sm:leading-6' . 
                ($error ? ' border-red-300 focus:border-red-500 focus:ring-red-500' : '')
            ]) }}
        />
    </div>

    @if($error)
        <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>