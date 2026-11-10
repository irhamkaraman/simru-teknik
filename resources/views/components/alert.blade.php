@props([
    'type' => 'info',
])

@php
$typeClasses = match($type) {
    'success' => 'bg-emerald-500/10 border-emerald-500/20 text-emerald-950',
    'danger' => 'bg-[#A1553E]/10 border-[#A1553E]/20 text-[#4A2418]',
    'warning' => 'bg-amber-500/10 border-amber-500/20 text-amber-950',
    default => 'bg-[#3C2A21]/5 border-[#3C2A21]/15 text-[#271A15]',
};
@endphp

<div {{ $attributes->merge(['class' => 'p-5 sm:p-6 rounded-2xl border backdrop-blur-md text-sm font-heading flex items-start space-x-3.5 ' . $typeClasses]) }}>
    <div class="flex-1">
        {{ $slot }}
    </div>
</div>
