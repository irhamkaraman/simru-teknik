@props([
    'color' => 'gray',
])

@php
$dotClasses = match($color) {
    'green' => 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]',
    'red' => 'bg-[#A1553E] shadow-[0_0_8px_rgba(161,85,62,0.6)]',
    'yellow' => 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)]',
    'blue' => 'bg-sky-500 shadow-[0_0_8px_rgba(14,165,233,0.6)]',
    'purple' => 'bg-[#6D4C41] shadow-[0_0_8px_rgba(109,76,65,0.6)]',
    default => 'bg-[#8C7A70]',
};

$textClasses = match($color) {
    'green' => 'text-emerald-950',
    'red' => 'text-[#4A2418]',
    'yellow' => 'text-amber-950',
    'blue' => 'text-sky-950',
    'purple' => 'text-[#3E2723]',
    default => 'text-[#3C2A21]',
};

$bgClasses = match($color) {
    'green' => 'bg-emerald-500/10 border-emerald-500/15',
    'red' => 'bg-[#A1553E]/10 border-[#A1553E]/15',
    'yellow' => 'bg-amber-500/10 border-amber-500/15',
    'blue' => 'bg-sky-500/10 border-sky-500/15',
    'purple' => 'bg-[#6D4C41]/10 border-[#6D4C41]/15',
    default => 'bg-stone-500/10 border-stone-500/15',
};
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full text-xs font-heading font-semibold tracking-wide border ' . $bgClasses . ' ' . $textClasses]) }}>
    <span class="w-2 h-2 rounded-full {{ $dotClasses }}"></span>
    <span>{{ $slot }}</span>
</span>
