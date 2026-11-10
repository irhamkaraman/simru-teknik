@props([
    'name',
    'title' => null,
])

<div
    x-data="{ show: false }"
    x-show="show"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') show = false"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto"
>
    <div class="fixed inset-0 bg-[#271A15]/60 backdrop-blur-md transition-opacity" x-on:click="show = false"></div>
    <div class="min-h-full flex items-center justify-center p-4 sm:p-6">
        <div
            class="relative glass-card bg-[#FAF7F2]/95 rounded-3xl max-w-lg w-full p-8 sm:p-10 shadow-2xl border border-white/80"
            x-on:click.stop
        >
            @if($title)
                <div class="flex items-center justify-between pb-5 mb-6 border-b border-[#3C2A21]/10">
                    <h3 class="font-heading text-xl sm:text-2xl font-bold text-[#271A15] tracking-tight">{{ $title }}</h3>
                    <button
                        type="button"
                        x-on:click="show = false"
                        class="p-2 rounded-xl text-[#3C2A21]/50 hover:text-[#271A15] hover:bg-black/5 transition-colors focus:outline-none"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
