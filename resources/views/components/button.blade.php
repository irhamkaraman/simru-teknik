@props([
    'variant' => 'primary',
    'type' => 'button',
    'size' => 'md',
])

@php
$sizeClasses = match($size) {
    'sm' => 'px-3.5 py-1.5 text-xs',
    'lg' => 'px-7 py-3.5 text-base sm:text-lg',
    default => 'px-5 py-2.5 sm:px-6 sm:py-3 text-sm font-bold',
};

$variantClasses = match($variant) {
    'primary' => 'glossy-button-primary',
    'secondary' => 'glossy-button-secondary',
    'terracotta' => 'glossy-button-terracotta',
    'danger' => 'bg-rose-700 hover:bg-rose-800 text-white shadow-sm',
    'outline' => 'bg-white/40 hover:bg-white/80 text-[#3C2A21] border border-[#3C2A21]/20',
    default => 'glossy-button-primary',
};
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-xl font-heading tracking-wide transition-all focus:outline-none focus:ring-2 focus:ring-[#A1553E]/40 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed ' . $sizeClasses . ' ' . $variantClasses]) }}
>
    {{ $slot }}
</button>
