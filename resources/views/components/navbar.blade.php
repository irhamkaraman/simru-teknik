<div class="md:hidden sticky top-0 z-40 bg-[#FAF7F2]/85 backdrop-blur-xl border-b border-[#3C2A21]/10 px-4 py-3.5 flex items-center justify-between">
    <a href="{{ route('rooms.index') }}" class="flex items-center space-x-3">
        <img
            src="{{ asset('assets/images/logo.svg') }}"
            alt="SIMRU Teknik Logo Merak"
            class="w-10 h-10 rounded-xl shadow-md object-contain"
        />
        <div>
            <span class="text-lg font-heading font-extrabold text-[#271A15] tracking-tight block leading-tight">
                SIMRU<span class="text-[#A1553E] font-medium ml-1">Teknik</span>
            </span>
            <span class="text-[10px] uppercase tracking-widest text-[#3C2A21]/60 font-heading block">
                Smart Room Booking
            </span>
        </div>
    </a>
    <a
        href="{{ route('bookings.create') }}"
        class="inline-flex items-center px-3.5 py-1.5 rounded-xl font-heading text-xs font-bold glossy-button-primary"
    >
        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
        </svg>
        Booking
    </a>
</div>

<aside class="fixed right-6 top-1/2 -translate-y-1/2 z-50 hidden md:flex flex-col items-center">
    <nav class="glass-card bg-[#271A15]/95 backdrop-blur-2xl rounded-full py-5 px-3 shadow-[0_20px_50px_rgba(25,15,12,0.45)] border border-white/20 flex flex-col items-center space-y-5">
        <div class="relative group flex items-center justify-center">
            <a
                href="{{ route('rooms.index') }}"
                class="w-11 h-11 rounded-full p-1.5 flex items-center justify-center hover:scale-110 active:scale-95 transition-transform"
            >
                <img
                    src="{{ asset('assets/images/logo.svg') }}"
                    alt="Logo Merak"
                    class="w-full h-full object-contain rounded-full shadow-sm"
                />
            </a>
            <div class="absolute right-full mr-3.5 px-3 py-1.5 rounded-xl bg-[#271A15] text-[#FAF7F2] font-heading text-xs font-semibold whitespace-nowrap opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 pointer-events-none shadow-xl border border-white/10 flex items-center">
                <span>SIMRU Teknik - Beranda</span>
                <div class="absolute -right-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-[#271A15] rotate-45 border-r border-t border-white/10"></div>
            </div>
        </div>

        <div class="w-6 h-px bg-white/10"></div>

        <div class="relative group flex items-center justify-center">
            <a
                href="{{ route('rooms.index') }}"
                class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('rooms.index') || request()->routeIs('rooms.show') ? 'bg-[#FAF7F2] text-[#271A15] shadow-md' : 'text-[#FAF7F2]/60 hover:text-[#FAF7F2] hover:bg-white/10' }}"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </a>
            <div class="absolute right-full mr-3.5 px-3 py-1.5 rounded-xl bg-[#271A15] text-[#FAF7F2] font-heading text-xs font-semibold whitespace-nowrap opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 pointer-events-none shadow-xl border border-white/10 flex items-center">
                <span>Ruangan & Laboratorium</span>
                <div class="absolute -right-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-[#271A15] rotate-45 border-r border-t border-white/10"></div>
            </div>
        </div>

        <div class="relative group flex items-center justify-center">
            <a
                href="{{ route('bookings.index') }}"
                class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('bookings.index') || request()->routeIs('bookings.ticket') ? 'bg-[#FAF7F2] text-[#271A15] shadow-md' : 'text-[#FAF7F2]/60 hover:text-[#FAF7F2] hover:bg-white/10' }}"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </a>
            <div class="absolute right-full mr-3.5 px-3 py-1.5 rounded-xl bg-[#271A15] text-[#FAF7F2] font-heading text-xs font-semibold whitespace-nowrap opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 pointer-events-none shadow-xl border border-white/10 flex items-center">
                <span>Jadwal Reservasi</span>
                <div class="absolute -right-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-[#271A15] rotate-45 border-r border-t border-white/10"></div>
            </div>
        </div>

        <div class="relative group flex items-center justify-center">
            <a
                href="{{ route('qr.scanner') }}"
                class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all {{ request()->routeIs('qr.scanner') ? 'bg-[#FAF7F2] text-[#271A15] shadow-md' : 'text-[#FAF7F2]/60 hover:text-[#FAF7F2] hover:bg-white/10' }}"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
            </a>
            <div class="absolute right-full mr-3.5 px-3 py-1.5 rounded-xl bg-[#271A15] text-[#FAF7F2] font-heading text-xs font-semibold whitespace-nowrap opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 pointer-events-none shadow-xl border border-white/10 flex items-center">
                <span>Layanan E-Tiket</span>
                <div class="absolute -right-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-[#271A15] rotate-45 border-r border-t border-white/10"></div>
            </div>
        </div>

        <div class="w-6 h-px bg-white/10"></div>

        <div class="relative group flex items-center justify-center">
            <a
                href="{{ route('bookings.create') }}"
                class="w-11 h-11 rounded-full bg-gradient-to-tr from-[#A1553E] via-[#B86850] to-[#D4A373] text-white flex items-center justify-center shadow-[0_8px_20px_rgba(161,85,62,0.6)] hover:scale-110 active:scale-95 transition-all"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
            </a>
            <div class="absolute right-full mr-3.5 px-3 py-1.5 rounded-xl bg-[#A1553E] text-white font-heading text-xs font-bold whitespace-nowrap opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 pointer-events-none shadow-xl border border-white/20 flex items-center">
                <span>Booking Cepat Ruangan</span>
                <div class="absolute -right-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-[#A1553E] rotate-45 border-r border-t border-white/20"></div>
            </div>
        </div>
    </nav>
</aside>

<div class="fixed bottom-4 inset-x-4 z-50 md:hidden flex justify-center pointer-events-none">
    <div class="pointer-events-auto max-w-sm w-full bg-[#271A15]/95 backdrop-blur-2xl rounded-full px-3 py-2 shadow-[0_16px_35px_rgba(25,15,12,0.45)] border border-white/20 flex items-center justify-between">
        <a
            href="{{ route('rooms.index') }}"
            class="flex-1 flex flex-col items-center justify-center py-1 rounded-full transition-colors {{ request()->routeIs('rooms.index') || request()->routeIs('rooms.show') ? 'text-[#D4A373]' : 'text-[#FAF7F2]/65 hover:text-[#FAF7F2]' }}"
        >
            <svg class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="text-[10px] font-heading font-semibold">Ruangan</span>
        </a>

        <a
            href="{{ route('bookings.index') }}"
            class="flex-1 flex flex-col items-center justify-center py-1 rounded-full transition-colors {{ request()->routeIs('bookings.index') || request()->routeIs('bookings.ticket') ? 'text-[#D4A373]' : 'text-[#FAF7F2]/65 hover:text-[#FAF7F2]' }}"
        >
            <svg class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="text-[10px] font-heading font-semibold">Jadwal</span>
        </a>

        <div class="px-2">
            <a
                href="{{ route('bookings.create') }}"
                class="w-13 h-13 -mt-6 p-3 rounded-full bg-gradient-to-tr from-[#A1553E] via-[#B86850] to-[#D4A373] text-white shadow-[0_8px_20px_rgba(161,85,62,0.6)] border-4 border-[#FAF7F2] flex items-center justify-center hover:scale-105 active:scale-95 transition-transform"
                title="Booking Ruangan Cepat"
            >
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
            </a>
        </div>

        <a
            href="{{ route('qr.scanner') }}"
            class="flex-1 flex flex-col items-center justify-center py-1 rounded-full transition-colors {{ request()->routeIs('qr.scanner') ? 'text-[#D4A373]' : 'text-[#FAF7F2]/65 hover:text-[#FAF7F2]' }}"
        >
            <svg class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
            <span class="text-[10px] font-heading font-semibold">E-Tiket</span>
        </a>
    </div>
</div>
