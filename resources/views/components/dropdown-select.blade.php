@props([
    'options' => [],
    'placeholder' => 'Pilih Opsi',
    'id' => null,
])

@php
$wireModel = $attributes->wire('model')->value() ?: $attributes->wire('model.live')->value();
$isLive = $attributes->wire('model.live')->value() !== null;
@endphp

<div
    x-data="{
        open: false,
        value: @if($wireModel) @entangle($attributes->wire('model')) @else '' @endif,
        options: {{ json_encode($options) }},
        placeholder: '{{ $placeholder }}',
        get selectedLabel() {
            let found = this.options.find(item => String(item.value) === String(this.value));
            return found ? found.label : this.placeholder;
        },
        select(val) {
            this.value = val;
            this.open = false;
            @if($wireModel)
                $wire.set('{{ $wireModel }}', val);
            @endif
        }
    }"
    class="relative w-full"
    :class="{ 'z-50': open, 'z-20': !open }"
    x-on:click.outside="open = false"
    x-on:keydown.escape.window="open = false"
>
    <button
        type="button"
        @if($id) id="{{ $id }}" @endif
        x-on:click="open = !open"
        class="w-full pl-5 pr-12 py-3.5 rounded-2xl glass-input font-heading text-sm text-[#271A15] flex items-center justify-between text-left focus:outline-none transition-all cursor-pointer relative"
    >
        <span class="truncate block" x-text="selectedLabel"></span>
        <span class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#3C2A21]/70 transition-transform duration-200" :class="{ 'rotate-180': open }">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
            </svg>
        </span>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
        style="display: none;"
        class="absolute z-[60] left-0 right-0 mt-2 p-2 rounded-2xl glass-card bg-[#FAF7F2]/98 shadow-[0_20px_50px_rgba(39,26,20,0.25)] border border-white/90 max-h-64 overflow-y-auto"
    >
        <template x-for="opt in options" :key="String(opt.value)">
            <button
                type="button"
                x-on:click="select(opt.value)"
                class="w-full px-4 py-2.5 rounded-xl text-left font-heading text-sm transition-colors flex items-center justify-between mb-1 last:mb-0"
                :class="String(value) === String(opt.value) ? 'bg-[#3C2A21] text-[#FAF7F2] font-bold shadow-sm' : 'text-[#271A15] hover:bg-black/5 font-medium'"
            >
                <span class="truncate pr-2" x-text="opt.label"></span>
                <span x-show="String(value) === String(opt.value)" class="text-[#D4A373] shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
            </button>
        </template>
    </div>
</div>
