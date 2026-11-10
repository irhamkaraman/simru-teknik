<div>
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('rooms.index') }}" class="inline-flex items-center font-heading text-sm font-semibold text-[#3C2A21]/70 hover:text-[#271A15] transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Ruangan
        </a>
        @if($currentStatus['state'] === 'maintenance')
            <button
                type="button"
                disabled
                class="inline-flex items-center px-6 py-3 rounded-xl font-heading text-sm font-bold bg-rose-50 text-rose-800 border border-rose-200 opacity-90 cursor-not-allowed shadow-sm"
            >
                Sedang Pemeliharaan (Tidak Dapat Dipesan)
            </button>
        @else
            <a
                href="{{ route('bookings.create', ['room_id' => $room->id]) }}"
                class="inline-flex items-center px-6 py-3 rounded-xl font-heading text-sm font-bold glossy-button-primary shadow-lg"
            >
                <svg class="w-4 h-4 mr-2 text-[#FAF7F2]/90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Reservasi Ruangan Ini
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-12">
        <div class="lg:col-span-2 space-y-10">
            <div class="glass-card rounded-3xl p-8 sm:p-12">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <span class="px-4 py-1.5 rounded-xl glass-pill font-heading text-xs font-bold text-[#3C2A21] tracking-wider uppercase">
                            {{ $room->code }}
                        </span>
                        @php
                            $statusStyle = match($currentStatus['state']) {
                                'maintenance' => 'bg-rose-500/10 text-rose-950 border-rose-500/20',
                                'occupied' => 'bg-[#A1553E]/10 text-[#5A2417] border-[#A1553E]/20',
                                'soon' => 'bg-amber-500/10 text-amber-950 border-amber-500/20',
                                default => 'bg-emerald-500/10 text-emerald-950 border-emerald-500/20',
                            };
                        @endphp
                        <span class="px-4 py-1.5 rounded-xl font-heading text-xs font-bold tracking-wide uppercase border {{ $statusStyle }}">
                            {{ $currentStatus['label'] }}
                        </span>
                    </div>
                    <span class="font-heading text-xs uppercase tracking-wider text-[#3C2A21]/70 font-semibold">
                        Kapasitas: <strong class="text-[#271A15] text-sm">{{ $room->capacity }} Orang</strong>
                    </span>
                </div>

                <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#271A15] tracking-tight leading-tight mb-3">
                    {{ $room->name }}
                </h1>
                <p class="font-heading text-sm font-semibold text-[#A1553E] uppercase tracking-wider mb-8 flex items-center">
                    <svg class="w-4 h-4 mr-2 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $room->location }}
                </p>

                <div class="mb-10">
                    <h4 class="font-heading text-xs font-bold uppercase tracking-wider text-[#3C2A21]/60 mb-3">Deskripsi Ruang</h4>
                    <p class="font-body text-base text-[#3C2A21]/80 leading-relaxed">{{ $room->description }}</p>
                </div>

                <div>
                    <h4 class="font-heading text-xs font-bold uppercase tracking-wider text-[#3C2A21]/60 mb-4">Fasilitas Penunjang</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($room->facilities ?? [] as $facility)
                            <div class="flex items-center space-x-3 p-3.5 rounded-2xl bg-[#3C2A21]/5 text-xs font-heading font-semibold text-[#271A15]">
                                <svg class="w-4 h-4 text-[#A1553E] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{{ $facility }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-8 sm:p-12">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-[#3C2A21]/10">
                    <div>
                        <h3 class="font-heading text-2xl font-bold text-[#271A15] tracking-tight">Jadwal Penggunaan Hari Ini</h3>
                        <p class="font-body text-xs text-[#3C2A21]/70 mt-1">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($todaySchedule as $booking)
                        <div class="p-5 rounded-2xl glass-card bg-white/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center space-x-3 mb-1">
                                    <span class="font-heading text-xs font-bold px-3 py-1 rounded-lg bg-[#3C2A21] text-[#FAF7F2]">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB
                                    </span>
                                    <span class="font-heading text-base font-bold text-[#271A15]">{{ $booking->title }}</span>
                                </div>
                                <p class="font-body text-xs text-[#3C2A21]/70">
                                    Peminjam: <strong class="text-[#271A15]">{{ $booking->user->name }}</strong> ({{ $booking->user->department ?? 'Civitas Teknik' }})
                                </p>
                            </div>
                            <x-badge :color="$booking->status === 'checked_in' ? 'green' : 'blue'">
                                {{ $booking->status === 'checked_in' ? 'Aktif (Checked In)' : 'Terkonfirmasi' }}
                            </x-badge>
                        </div>
                    @empty
                        <div class="py-10 text-center glass-pill rounded-2xl p-6">
                            <p class="font-body text-sm text-[#3C2A21]/70">Tidak ada jadwal pemakaian hari ini. Ruangan bebas dipesan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="glass-card rounded-3xl p-8 sm:p-12">
                <h3 class="font-heading text-2xl font-bold text-[#271A15] tracking-tight mb-6 pb-4 border-b border-[#3C2A21]/10">Agenda 7 Hari Mendatang</h3>
                <div class="space-y-3">
                    @forelse($upcomingSchedule as $booking)
                        <div class="p-4 rounded-2xl bg-white/40 border border-white/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div>
                                <span class="font-heading font-bold text-sm text-[#271A15] block">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('l, d M Y') }} &bull; {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB
                                </span>
                                <span class="font-body text-[#3C2A21]/70 mt-0.5 block">{{ $booking->title }} ({{ $booking->user->name }})</span>
                            </div>
                            <span class="font-heading font-mono text-[11px] text-[#A1553E] bg-[#A1553E]/10 px-2.5 py-1 rounded-lg self-start sm:self-auto">
                                {{ $booking->booking_code }}
                            </span>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs font-body text-[#3C2A21]/60">
                            Belum ada jadwal pemesanan mendatang.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="glass-card rounded-3xl p-8 sm:p-10">
                <span class="font-heading text-xs font-bold uppercase tracking-widest text-[#A1553E] block mb-2">Identitas Resmi Ruangan</span>
                <h3 class="font-heading text-2xl font-bold text-[#271A15] mb-1">{{ $room->code }}</h3>
                <p class="font-body text-xs text-[#3C2A21]/70 mb-6">Gunakan ID ini untuk pencarian cepat atau konfirmasi jadwal.</p>

                <div class="p-5 rounded-2xl bg-white/60 border border-white/80 space-y-3.5 text-xs font-heading">
                    <div class="flex justify-between items-center py-1 border-b border-[#3C2A21]/5">
                        <span class="text-[#3C2A21]/60 uppercase tracking-wider">ID Ruangan</span>
                        <span class="font-mono font-bold text-[#271A15] bg-[#3C2A21]/5 px-2.5 py-1 rounded-lg">{{ $room->qr_identifier }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-[#3C2A21]/5">
                        <span class="text-[#3C2A21]/60 uppercase tracking-wider">Lokasi</span>
                        <span class="font-bold text-[#271A15] text-right">{{ $room->location }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-[#3C2A21]/60 uppercase tracking-wider">Kapasitas Maksimal</span>
                        <span class="font-bold text-[#271A15]">{{ $room->capacity }} Orang</span>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-8 sm:p-10 bg-gradient-to-br from-[#F4EFE6] to-[#FAF7F2]">
                <h4 class="font-heading text-lg font-bold text-[#271A15] mb-3">Aturan Peminjaman</h4>
                <ul class="font-body text-xs text-[#3C2A21]/80 space-y-3">
                    <li class="flex items-start space-x-2.5">
                        <svg class="w-4 h-4 text-[#A1553E] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4" />
                        </svg>
                        <span>Booking dapat diajukan secara online untuk jadwal yang masih tersedia.</span>
                    </li>
                    <li class="flex items-start space-x-2.5">
                        <svg class="w-4 h-4 text-[#A1553E] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4" />
                        </svg>
                        <span>Permohonan reservasi akan diverifikasi dan disetujui oleh Admin Lab Teknik.</span>
                    </li>
                    <li class="flex items-start space-x-2.5">
                        <svg class="w-4 h-4 text-[#A1553E] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4" />
                        </svg>
                        <span>Gunakan ruangan sesuai jam dan keperluan yang telah disetujui secara tertib.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
