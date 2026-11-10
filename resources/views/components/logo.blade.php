@props([
    'size' => 'md',
    'showText' => false,
])

@php
$dimensions = match($size) {
    'xs' => 'w-7 h-7',
    'sm' => 'w-9 h-9',
    'lg' => 'w-16 h-16',
    'xl' => 'w-24 h-24',
    default => 'w-12 h-12',
};
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center space-x-3.5']) }}>
    <img
        src="{{ asset('assets/images/logo.svg') }}"
        alt="SIMRU Teknik Logo"
        class="{{ $dimensions }} rounded-2xl shadow-md object-contain transition-transform duration-300 hover:scale-105"
    />
    @if($showText)
        <div>
            <span class="text-xl sm:text-2xl font-heading font-extrabold text-[#271A15] tracking-tight block leading-tight">
                SIMRU<span class="text-[#A1553E] font-medium ml-1">Teknik</span>
            </span>
            <span class="text-[10px] sm:text-xs uppercase tracking-widest text-[#3C2A21]/60 font-heading block">
                Smart Room Booking
            </span>
        </div>
    @endif
</div>
