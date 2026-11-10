@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'glass-card rounded-3xl overflow-hidden transition-all duration-300']) }}>
    @if($title || isset($header))
        <div class="px-8 sm:px-10 py-6 border-b border-[#3C2A21]/5 flex flex-wrap items-center justify-between gap-4 bg-white/40">
            <div>
                @if($title)
                    <h3 class="font-heading text-xl sm:text-2xl font-bold text-[#271A15] tracking-tight">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs sm:text-sm text-[#3C2A21]/70 mt-1 font-body">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($header)
                <div>{{ $header }}</div>
            @endisset
        </div>
    @endif
    <div class="p-8 sm:p-10">
        {{ $slot }}
    </div>
    @isset($footer)
        <div class="px-8 sm:px-10 py-5 bg-[#FAF7F2]/60 border-t border-[#3C2A21]/5">
            {{ $footer }}
        </div>
    @endisset
</div>
