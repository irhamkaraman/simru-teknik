<div>
    <section class="mb-12 sm:mb-16">
        <div class="relative overflow-hidden glass-card rounded-3xl p-8 sm:p-12 lg:p-16">
            <div class="relative z-10 max-w-3xl">
                <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#271A15] tracking-tight leading-[1.1] mb-6">
                    Akses Cepat Laboratorium & Ruang Kolaborasi Teknik
                </h1>
                <p class="font-body text-base sm:text-lg text-[#3C2A21]/80 leading-relaxed mb-8 sm:mb-10 max-w-2xl">
                    Periksa jadwal real-time, lakukan reservasi instan tanpa birokrasi berbelit, dan dapatkan tiket QR digital untuk akses pintu otomatis.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a
                        href="{{ route('bookings.create') }}"
                        class="inline-flex items-center px-8 py-4 rounded-2xl font-heading text-base font-bold glossy-button-primary shadow-xl"
                    >
                        <span>Reservasi Ruangan Sekarang</span>
                        <svg class="w-5 h-5 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a
                        href="{{ route('qr.scanner') }}"
                        class="inline-flex items-center px-6 py-4 rounded-2xl font-heading text-base font-semibold glossy-button-secondary"
                    >
                        <svg class="w-5 h-5 mr-2.5 text-[#A1553E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Cari ID Ruangan</span>
                    </a>
                </div>
            </div>
            <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-gradient-to-br from-[#D4A373]/20 to-[#A1553E]/10 blur-3xl pointer-events-none"></div>
        </div>
    </section>

    <div class="glass-card rounded-2xl p-4 sm:p-6 mb-10 flex flex-col md:flex-row items-center gap-4 relative z-30">
        <div class="flex-1 w-full relative">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nama laboratorium, kode (LAB-IOT), atau gedung..."
                class="w-full pl-12 pr-10 py-3.5 rounded-2xl glass-input font-heading text-sm text-[#271A15] placeholder-[#3C2A21]/40 focus:outline-none"
            />
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-[#3C2A21]/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <svg class="animate-spin h-5 w-5 text-[#A1553E]" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
            <div class="w-full sm:w-56">
                <x-dropdown-select
                    wire:model.live="statusFilter"
                    placeholder="Semua Status"
                    :options="[
                        ['value' => '', 'label' => 'Semua Status'],
                        ['value' => 'available', 'label' => 'Tersedia Saja'],
                        ['value' => 'maintenance', 'label' => 'Dalam Pemeliharaan'],
                    ]"
                />
            </div>
            <div class="w-full sm:w-56">
                <x-dropdown-select
                    wire:model.live="minCapacity"
                    placeholder="Semua Kapasitas"
                    :options="[
                        ['value' => '', 'label' => 'Semua Kapasitas'],
                        ['value' => '15', 'label' => 'Minimal 15 Orang'],
                        ['value' => '30', 'label' => 'Minimal 30 Orang'],
                        ['value' => '40', 'label' => 'Minimal 40 Orang'],
                    ]"
                />
            </div>
        </div>
    </div>

    <div wire:loading.grid wire:target="search, statusFilter, minCapacity" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8 relative z-10">
        @for($i = 0; $i < 6; $i++)
            <div class="glass-card rounded-3xl p-8 animate-pulse flex flex-col justify-between min-h-[380px]">
                <div>
                    <div class="flex justify-between items-center mb-6">
                        <div class="h-6 w-24 bg-[#3C2A21]/10 rounded-xl"></div>
                        <div class="h-5 w-20 bg-[#3C2A21]/10 rounded-full"></div>
                    </div>
                    <div class="h-8 w-4/5 bg-[#3C2A21]/15 rounded-xl mb-3"></div>
                    <div class="h-4 w-1/2 bg-[#3C2A21]/10 rounded-lg mb-6"></div>
                    <div class="space-y-2 mb-6">
                        <div class="h-3.5 w-full bg-[#3C2A21]/10 rounded"></div>
                        <div class="h-3.5 w-5/6 bg-[#3C2A21]/10 rounded"></div>
                    </div>
                    <div class="pt-4 border-t border-[#3C2A21]/5 space-y-2">
                        <div class="h-4 w-1/3 bg-[#3C2A21]/10 rounded"></div>
                        <div class="flex gap-2">
                            <div class="h-5 w-16 bg-[#3C2A21]/10 rounded-lg"></div>
                            <div class="h-5 w-20 bg-[#3C2A21]/10 rounded-lg"></div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 pt-4 border-t border-[#3C2A21]/5">
                    <div class="h-11 flex-1 bg-[#3C2A21]/10 rounded-xl"></div>
                    <div class="h-11 flex-1 bg-[#3C2A21]/15 rounded-xl"></div>
                </div>
            </div>
        @endfor
    </div>

    <div wire:loading.remove wire:target="search, statusFilter, minCapacity">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative z-10">
            @forelse($roomsData as $item)
                @php
                    $room = $item['model'];
                    $status = $item['status'];
                    $ribbonConfig = match($status['state']) {
                        'maintenance' => [
                            'bg' => 'bg-gradient-to-r from-rose-700 to-rose-900',
                            'text' => 'Pemeliharaan',
                        ],
                        'occupied' => [
                            'bg' => 'bg-gradient-to-r from-[#8C3D2B] to-[#5A2417]',
                            'text' => 'Terpakai',
                        ],
                        'soon' => [
                            'bg' => 'bg-gradient-to-r from-amber-600 to-amber-700',
                            'text' => 'Akan Digunakan',
                        ],
                        default => [
                            'bg' => 'bg-gradient-to-r from-emerald-600 to-emerald-700',
                            'text' => 'Tersedia',
                        ],
                    };
                @endphp
                <div class="glass-card glass-card-hover rounded-3xl p-8 flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-36 h-36 overflow-hidden pointer-events-none z-10">
                        <div class="absolute top-6 -right-9 w-44 py-1 text-center font-heading text-[10px] font-black uppercase tracking-wider text-white shadow-md rotate-45 {{ $ribbonConfig['bg'] }}">
                            {{ $ribbonConfig['text'] }}
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center mb-5">
                            <span class="px-3.5 py-1.5 rounded-xl glass-pill font-heading text-xs font-bold text-[#3C2A21] tracking-wider uppercase">
                                {{ $room->code }}
                            </span>
                        </div>

                        <h3 class="font-heading text-2xl font-bold text-[#271A15] tracking-tight leading-snug mb-2 pr-12">
                            <a href="{{ route('rooms.show', $room->slug) }}" class="hover:text-[#A1553E] transition-colors">
                                {{ $room->name }}
                            </a>
                        </h3>

                        <p class="font-heading text-xs font-semibold text-[#A1553E] uppercase tracking-wider mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $room->location }}
                        </p>

                        <p class="font-body text-sm text-[#3C2A21]/80 leading-relaxed mb-6 line-clamp-2">
                            {{ $room->description }}
                        </p>

                        <div class="pt-4 border-t border-[#3C2A21]/5 mb-6">
                            <div class="flex items-center justify-between font-heading text-xs font-semibold text-[#3C2A21]/70 mb-3">
                                <span>Kapasitas</span>
                                <span class="text-[#271A15] font-bold text-sm">{{ $room->capacity }} Orang</span>
                            </div>

                            @if(!empty($room->facilities))
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_slice($room->facilities, 0, 3) as $fac)
                                        <span class="px-2.5 py-1 rounded-lg bg-[#3C2A21]/5 font-heading text-[11px] font-medium text-[#3C2A21]">
                                            {{ $fac }}
                                        </span>
                                    @endforeach
                                    @if(count($room->facilities) > 3)
                                        <span class="px-2 py-1 rounded-lg bg-[#3C2A21]/5 font-heading text-[11px] font-medium text-[#3C2A21]/60">
                                            +{{ count($room->facilities) - 3 }} lainnya
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-[#3C2A21]/5">
                        <a
                            href="{{ route('rooms.show', $room->slug) }}"
                            class="flex-1 py-3 px-4 rounded-xl font-heading text-xs font-bold text-center glossy-button-secondary"
                        >
                            Jadwal & Info
                        </a>
                        @if($status['state'] === 'maintenance')
                            <button
                                type="button"
                                disabled
                                class="flex-1 py-3 px-4 rounded-xl font-heading text-xs font-bold text-center bg-rose-50 text-rose-800 border border-rose-200 cursor-not-allowed opacity-80"
                            >
                                Pemeliharaan
                            </button>
                        @else
                            <a
                                href="{{ route('bookings.create', ['room_id' => $room->id]) }}"
                                class="flex-1 py-3 px-4 rounded-xl font-heading text-xs font-bold text-center glossy-button-primary"
                            >
                                Booking
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center glass-card rounded-3xl p-10">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-[#3C2A21]/5 flex items-center justify-center text-[#3C2A21]/40 mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold text-[#271A15]">Tidak Ada Ruangan Sesuai Kriteria</h3>
                    <p class="font-body text-sm text-[#3C2A21]/70 mt-2">Coba ubah kata kunci pencarian atau reset filter kapasitas di atas.</p>
                </div>
            @endforelse
        </div>

        <div wire:loading.grid wire:target="loadMore" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
            @for($j = 0; $j < 3; $j++)
                <div class="glass-card rounded-3xl p-8 animate-pulse flex flex-col justify-between min-h-[380px]">
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <div class="h-6 w-24 bg-[#3C2A21]/10 rounded-xl"></div>
                            <div class="h-5 w-20 bg-[#3C2A21]/10 rounded-full"></div>
                        </div>
                        <div class="h-8 w-4/5 bg-[#3C2A21]/15 rounded-xl mb-3"></div>
                        <div class="h-4 w-1/2 bg-[#3C2A21]/10 rounded-lg mb-6"></div>
                        <div class="space-y-2 mb-6">
                            <div class="h-3.5 w-full bg-[#3C2A21]/10 rounded"></div>
                            <div class="h-3.5 w-5/6 bg-[#3C2A21]/10 rounded"></div>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-[#3C2A21]/5">
                        <div class="h-11 flex-1 bg-[#3C2A21]/10 rounded-xl"></div>
                        <div class="h-11 flex-1 bg-[#3C2A21]/15 rounded-xl"></div>
                    </div>
                </div>
            @endfor
        </div>

        @if($hasMore)
            <div class="mt-12 text-center flex flex-col items-center">
                <span class="font-heading text-xs font-semibold text-[#3C2A21]/60 mb-3">
                    Menampilkan {{ $showingCount }} dari {{ $totalMatchingRooms }} Ruangan & Lab
                </span>
                <button
                    wire:click="loadMore"
                    wire:loading.attr="disabled"
                    wire:target="loadMore"
                    class="px-8 py-4 rounded-2xl font-heading text-sm font-bold glossy-button-secondary shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center space-x-2"
                >
                    <span wire:loading.remove wire:target="loadMore">Tampilkan 9 Ruangan Lagi</span>
                    <span wire:loading wire:target="loadMore" class="flex items-center space-x-2">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-[#A1553E]" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Memuat Ruangan...</span>
                    </span>
                    <svg wire:loading.remove wire:target="loadMore" class="w-4 h-4 ml-1 text-[#A1553E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
        @elseif($totalMatchingRooms > 9)
            <div class="mt-10 text-center font-heading text-xs font-semibold text-[#3C2A21]/50">
                Seluruh {{ $totalMatchingRooms }} ruangan dan laboratorium telah ditampilkan.
            </div>
        @endif
    </div>
</div>
