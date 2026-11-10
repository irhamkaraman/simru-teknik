<div>
    <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div>
            <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#271A15] tracking-tight leading-tight">
                Layanan E-Tiket & Cek Ruangan
            </h1>
            <p class="font-body text-base text-[#3C2A21]/80 mt-2">
                Periksa status permohonan reservasi atau cari informasi ruangan melalui ID resmi.
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button
                wire:click="$set('tab', 'checkin')"
                class="px-5 py-2.5 rounded-xl font-heading text-xs font-bold transition-all {{ $tab === 'checkin' ? 'glossy-button-primary' : 'glossy-button-secondary' }}"
            >
                Cek Status E-Tiket
            </button>
            <button
                wire:click="$set('tab', 'doorscan')"
                class="px-5 py-2.5 rounded-xl font-heading text-xs font-bold transition-all {{ $tab === 'doorscan' ? 'glossy-button-primary' : 'glossy-button-secondary' }}"
            >
                Cari ID Ruangan
            </button>
        </div>
    </div>

    @if($tab === 'checkin')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="glass-card rounded-3xl p-8 sm:p-12">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-[#3C2A21] text-[#FAF7F2] flex items-center justify-center font-heading font-bold shadow-md">
                        <svg class="w-6 h-6 text-[#D4A373]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-heading text-2xl font-bold text-[#271A15] tracking-tight">Cek Status E-Tiket Reservasi</h3>
                        <p class="font-body text-xs text-[#3C2A21]/70">Masukkan kode booking untuk melihat status pengajuan Anda</p>
                    </div>
                </div>

                <form wire:submit="processBookingCode" class="space-y-6">
                    <div>
                        <label for="bookingCodeInput" class="block font-heading text-xs font-bold uppercase tracking-wider text-[#3C2A21]/70 mb-2">
                            Kode Reservasi E-Tiket
                        </label>
                        <input
                            id="bookingCodeInput"
                            type="text"
                            wire:model="bookingCodeInput"
                            placeholder="Contoh: BK-INIT-IOT01"
                            class="w-full px-4 py-3.5 rounded-2xl glass-input font-heading font-mono text-sm text-[#271A15] placeholder-[#3C2A21]/40 focus:outline-none"
                        />
                    </div>

                    @if($scanMessage)
                        <div class="p-4 rounded-2xl font-heading text-xs font-semibold border backdrop-blur-md {{ $scanStatus === 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-950' : 'bg-[#A1553E]/10 border-[#A1553E]/20 text-[#4A2418]' }}">
                            {{ $scanMessage }}
                        </div>
                    @endif

                    <button
                        type="submit"
                        class="w-full py-4 rounded-2xl font-heading text-sm font-bold glossy-button-primary shadow-xl"
                    >
                        Cari Data E-Tiket
                    </button>
                </form>
            </div>

            <div>
                @if($resolvedBooking)
                    <div class="glass-card rounded-3xl p-8 sm:p-12">
                        <div class="pb-6 border-b border-[#3C2A21]/10 mb-6">
                            @php
                                $statusStyle = match($resolvedBooking->status) {
                                    'pending' => 'bg-amber-500/10 text-amber-900 border-amber-500/20',
                                    'confirmed' => 'bg-emerald-500/10 text-emerald-950 border-emerald-500/20',
                                    'checked_in' => 'bg-sky-500/10 text-sky-950 border-sky-500/20',
                                    'completed' => 'bg-[#3C2A21]/10 text-[#3C2A21] border-[#3C2A21]/15',
                                    'rejected' => 'bg-rose-500/10 text-rose-900 border-rose-500/20',
                                    default => 'bg-stone-500/10 text-stone-800 border-stone-500/20',
                                };
                                $statusLabel = match($resolvedBooking->status) {
                                    'pending' => 'Menunggu Persetujuan',
                                    'confirmed' => 'Disetujui',
                                    'checked_in' => 'Sedang Berlangsung',
                                    'completed' => 'Selesai',
                                    'rejected' => 'Ditolak',
                                    default => strtoupper($resolvedBooking->status),
                                };
                            @endphp
                            <div class="flex items-center justify-between gap-4 mb-2">
                                <span class="font-heading font-mono text-xs font-bold text-[#A1553E] tracking-wider">{{ $resolvedBooking->booking_code }}</span>
                                <span class="px-3.5 py-1.5 rounded-xl font-heading text-xs font-bold tracking-wide uppercase border {{ $statusStyle }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <h3 class="font-heading text-2xl sm:text-3xl font-extrabold text-[#271A15] tracking-tight leading-snug">{{ $resolvedBooking->title }}</h3>
                        </div>

                        <div class="space-y-4 text-xs font-heading mb-8">
                            <div class="flex justify-between py-1.5 border-b border-[#3C2A21]/5">
                                <span class="text-[#3C2A21]/60 uppercase tracking-wider">Ruangan</span>
                                <span class="font-bold text-[#271A15]">{{ $resolvedBooking->room->name }} ({{ $resolvedBooking->room->code }})</span>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-[#3C2A21]/5">
                                <span class="text-[#3C2A21]/60 uppercase tracking-wider">Nama Pemohon</span>
                                <span class="font-bold text-[#271A15]">{{ $resolvedBooking->borrower_name }} ({{ $resolvedBooking->borrower_nim }})</span>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-[#3C2A21]/5">
                                <span class="text-[#3C2A21]/60 uppercase tracking-wider">No. WhatsApp</span>
                                <span class="font-bold text-[#271A15]">{{ $resolvedBooking->borrower_phone }}</span>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-[#3C2A21]/5">
                                <span class="text-[#3C2A21]/60 uppercase tracking-wider">Jadwal</span>
                                <span class="font-bold text-[#271A15]">
                                    {{ \Carbon\Carbon::parse($resolvedBooking->start_time)->translatedFormat('d M Y, H:i') }} - {{ \Carbon\Carbon::parse($resolvedBooking->end_time)->format('H:i') }} WIB
                                </span>
                            </div>
                            @if($resolvedBooking->cancellation_reason)
                                <div class="p-3 rounded-xl bg-rose-50 text-rose-900 text-xs">
                                    <span class="font-bold block mb-0.5">Catatan Penolakan/Pembatalan:</span>
                                    <span>{{ $resolvedBooking->cancellation_reason }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-end">
                            <a
                                href="{{ route('bookings.index') }}"
                                class="px-6 py-3 rounded-xl font-heading text-xs font-bold glossy-button-secondary"
                            >
                                Lihat Semua Jadwal
                            </a>
                        </div>
                    </div>
                @else
                    <div class="glass-card rounded-3xl p-12 text-center flex flex-col items-center justify-center min-h-[380px]">
                        <div class="w-16 h-16 rounded-2xl bg-[#3C2A21]/5 flex items-center justify-center text-[#3C2A21]/40 mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="font-heading text-xl font-bold text-[#271A15]">Menunggu Pencarian E-Tiket</h4>
                        <p class="font-body text-sm text-[#3C2A21]/70 max-w-sm mt-2">
                            Masukkan kode booking pada formulir untuk melihat rincian peminjaman dan status persetujuan dari Admin Lab Teknik.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    @elseif($tab === 'doorscan')
        <div class="max-w-2xl mx-auto glass-card rounded-3xl p-8 sm:p-12">
            <h3 class="font-heading text-2xl font-bold text-[#271A15] mb-2 tracking-tight">Cari Berdasarkan ID Ruangan</h3>
            <p class="font-body text-sm text-[#3C2A21]/70 mb-8">
                Masukkan ID ruangan resmi untuk langsung memeriksa jadwal ketersediaan dan mengajukan peminjaman.
            </p>

            <form wire:submit="processRoomIdentifier" class="space-y-6">
                <div>
                    <label for="roomIdentifierInput" class="block font-heading text-xs font-bold uppercase tracking-wider text-[#3C2A21]/70 mb-2">
                        ID Ruangan Resmi
                    </label>
                    <input
                        id="roomIdentifierInput"
                        type="text"
                        wire:model="roomIdentifierInput"
                        placeholder="Contoh: room-iot-robotika-dekanat3"
                        class="w-full px-4 py-3.5 rounded-2xl glass-input font-heading font-mono text-sm text-[#271A15] placeholder-[#3C2A21]/40 focus:outline-none"
                    />
                </div>

                @if($scanMessage)
                    <div class="p-4 rounded-2xl font-heading text-xs font-semibold border backdrop-blur-md {{ $scanStatus === 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-950' : 'bg-[#A1553E]/10 border-[#A1553E]/20 text-[#4A2418]' }}">
                        {{ $scanMessage }}
                    </div>
                @endif

                <button
                    type="submit"
                    class="w-full py-4 rounded-2xl font-heading text-sm font-bold glossy-button-primary shadow-xl"
                >
                    Buka Halaman Ruangan
                </button>
            </form>
        </div>
    @endif
</div>
