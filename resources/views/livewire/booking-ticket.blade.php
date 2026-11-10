<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('bookings.index') }}" class="inline-flex items-center font-heading text-sm font-semibold text-[#3C2A21]/70 hover:text-[#271A15] transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Jadwal Reservasi
        </a>
        <button
            type="button"
            onclick="window.print()"
            class="inline-flex items-center px-4 py-2 rounded-xl font-heading text-xs font-semibold glossy-button-secondary"
        >
            <svg class="w-4 h-4 mr-2 text-[#3C2A21]/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak E-Tiket
        </button>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl border border-white/80">
        <div class="bg-gradient-to-r from-[#2C1E1A] via-[#3C2A21] to-[#241712] p-8 sm:p-12 text-[#FAF7F2] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 relative overflow-hidden">
            <div class="relative z-10 flex items-center space-x-5">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo Merak SIMRU" class="w-14 h-14 rounded-2xl shadow-lg border border-white/20 shrink-0 object-contain">
                <div>
                    <span class="font-heading text-xs uppercase tracking-widest text-[#D4A373] font-bold block mb-1">E-Tiket Reservasi Resmi</span>
                    <h2 class="font-heading text-2xl sm:text-3xl font-bold tracking-tight text-white mb-1">{{ $booking->room->name }}</h2>
                    <p class="font-heading text-xs font-semibold text-[#FAF7F2]/70">{{ $booking->room->code }} &bull; {{ $booking->room->location }}</p>
                </div>
            </div>
            <div class="relative z-10 sm:text-right">
                <span class="font-heading text-xs uppercase tracking-wider text-[#FAF7F2]/60 block mb-1">Kode Booking</span>
                <span class="font-heading font-mono text-xl sm:text-2xl font-extrabold text-[#D4A373] tracking-wide">{{ $booking->booking_code }}</span>
            </div>
            <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/5 blur-2xl pointer-events-none"></div>
        </div>

        <div class="p-8 sm:p-12 flex flex-col items-center">
            <div class="mb-8 text-center">
                @php
                    $statusStyle = match($booking->status) {
                        'pending' => 'bg-amber-500/10 text-amber-900 border-amber-500/20',
                        'confirmed' => 'bg-emerald-500/10 text-emerald-950 border-emerald-500/20',
                        'checked_in' => 'bg-sky-500/10 text-sky-950 border-sky-500/20',
                        'completed' => 'bg-[#3C2A21]/10 text-[#3C2A21] border-[#3C2A21]/15',
                        'rejected' => 'bg-rose-500/10 text-rose-900 border-rose-500/20',
                        default => 'bg-stone-500/10 text-stone-800 border-stone-500/20',
                    };
                    $statusLabel = match($booking->status) {
                        'pending' => 'Menunggu Persetujuan Admin Lab',
                        'confirmed' => 'Disetujui (Confirmed)',
                        'checked_in' => 'Sedang Digunakan',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default => strtoupper($booking->status),
                    };
                @endphp
                <span class="px-5 py-2 rounded-2xl font-heading text-sm font-bold tracking-wide uppercase border {{ $statusStyle }}">
                    {{ $statusLabel }}
                </span>
                <p class="font-body text-xs text-[#3C2A21]/60 mt-3 max-w-sm">
                    Simpan kode booking ini sebagai bukti pengajuan dan izin pemakaian ruangan laboratorium.
                </p>
            </div>

            <div class="w-full glass-card bg-white/50 rounded-2xl p-6 sm:p-8 space-y-4 text-xs font-heading mb-8">
                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-[#3C2A21]/5 gap-1">
                    <span class="text-[#3C2A21]/60 uppercase tracking-wider text-[11px] font-bold">Nama Agenda</span>
                    <span class="font-bold text-sm text-[#271A15] sm:text-right">{{ $booking->title }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-[#3C2A21]/5 gap-1">
                    <span class="text-[#3C2A21]/60 uppercase tracking-wider text-[11px] font-bold">Nama Pemohon</span>
                    <span class="font-bold text-[#271A15] sm:text-right">{{ $booking->borrower_name }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-[#3C2A21]/5 gap-1">
                    <span class="text-[#3C2A21]/60 uppercase tracking-wider text-[11px] font-bold">NIM (8 Digit)</span>
                    <span class="font-mono font-bold text-[#271A15] sm:text-right">{{ $booking->borrower_nim }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-[#3C2A21]/5 gap-1">
                    <span class="text-[#3C2A21]/60 uppercase tracking-wider text-[11px] font-bold">No. WhatsApp</span>
                    <span class="font-bold text-[#271A15] sm:text-right">{{ $booking->borrower_phone }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-[#3C2A21]/5 gap-1">
                    <span class="text-[#3C2A21]/60 uppercase tracking-wider text-[11px] font-bold">Waktu Mulai</span>
                    <span class="font-bold text-[#271A15] sm:text-right">{{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('l, d M Y - H:i') }} WIB</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-[#3C2A21]/5 gap-1">
                    <span class="text-[#3C2A21]/60 uppercase tracking-wider text-[11px] font-bold">Waktu Selesai</span>
                    <span class="font-bold text-[#271A15] sm:text-right">{{ \Carbon\Carbon::parse($booking->end_time)->translatedFormat('l, d M Y - H:i') }} WIB</span>
                </div>
                @if($booking->purpose)
                    <div class="pt-2">
                        <span class="text-[#3C2A21]/60 uppercase tracking-wider text-[11px] font-bold block mb-1">Catatan Tambahan:</span>
                        <span class="font-body text-[#3C2A21]/80 text-xs italic">{{ $booking->purpose }}</span>
                    </div>
                @endif
                @if($booking->cancellation_reason)
                    <div class="pt-2 text-rose-800">
                        <span class="uppercase tracking-wider text-[11px] font-bold block mb-1">Alasan Penolakan / Pembatalan:</span>
                        <span class="font-body text-xs">{{ $booking->cancellation_reason }}</span>
                    </div>
                @endif
            </div>

            <div class="w-full flex flex-col sm:flex-row gap-4 justify-center">
                <a
                    href="{{ route('bookings.index') }}"
                    class="px-8 py-3.5 rounded-xl font-heading text-sm font-bold text-center glossy-button-secondary"
                >
                    Lihat Semua Jadwal
                </a>
            </div>
        </div>
    </div>
</div>
