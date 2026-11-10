<div>
    <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div>
            <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#271A15] tracking-tight leading-tight">
                Jadwal Pemakaian Ruangan
            </h1>
            <p class="font-body text-base text-[#3C2A21]/80 mt-2">
                Pantau jadwal perkuliahan, praktikum, dan agenda kegiatan resmi laboratorium Fakultas Teknik.
            </p>
        </div>
        <div>
            <a
                href="{{ route('bookings.create') }}"
                class="inline-flex items-center px-6 py-3.5 rounded-xl font-heading text-sm font-bold glossy-button-primary shadow-xl"
            >
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m8-8H4" />
                </svg>
                Ajukan Peminjaman
            </a>
        </div>
    </div>

    <div class="glass-card rounded-2xl p-4 sm:p-6 mb-8 flex flex-col md:flex-row items-center gap-4 relative z-20">
        <div class="flex-1 w-full relative">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari mata kuliah, nama dosen/pemohon, NIM, atau kode..."
                class="w-full pl-12 pr-4 py-3.5 rounded-2xl glass-input font-heading text-sm text-[#271A15] placeholder-[#3C2A21]/40 focus:outline-none"
            />
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-[#3C2A21]/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="w-full md:w-72">
            <x-dropdown-select
                wire:model.live="roomFilter"
                placeholder="Semua Ruangan & Lab"
                :options="array_merge(
                    [['value' => '', 'label' => 'Semua Ruangan & Lab']],
                    $rooms->map(fn($r) => ['value' => (string)$r->id, 'label' => $r->code . ' - ' . $r->name])->values()->all()
                )"
            />
        </div>
    </div>

    <div class="flex flex-wrap gap-2 mb-8">
        <button
            wire:click="$set('filter', 'all')"
            class="px-5 py-2.5 rounded-xl font-heading text-xs font-bold transition-all {{ $filter === 'all' ? 'glossy-button-primary' : 'glossy-button-secondary' }}"
        >
            Semua Jadwal
        </button>
        <button
            wire:click="$set('filter', 'ongoing')"
            class="px-5 py-2.5 rounded-xl font-heading text-xs font-bold transition-all {{ $filter === 'ongoing' ? 'glossy-button-primary' : 'glossy-button-secondary' }}"
        >
            Sedang Berlangsung
        </button>
        <button
            wire:click="$set('filter', 'upcoming')"
            class="px-5 py-2.5 rounded-xl font-heading text-xs font-bold transition-all {{ $filter === 'upcoming' ? 'glossy-button-primary' : 'glossy-button-secondary' }}"
        >
            Akan Datang
        </button>
        <button
            wire:click="$set('filter', 'pending')"
            class="px-5 py-2.5 rounded-xl font-heading text-xs font-bold transition-all {{ $filter === 'pending' ? 'glossy-button-primary' : 'glossy-button-secondary' }}"
        >
            Menunggu Persetujuan
        </button>
        <button
            wire:click="$set('filter', 'completed')"
            class="px-5 py-2.5 rounded-xl font-heading text-xs font-bold transition-all {{ $filter === 'completed' ? 'glossy-button-primary' : 'glossy-button-secondary' }}"
        >
            Selesai
        </button>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden shadow-xl mb-8">
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-[#3C2A21]/10 text-left">
                <thead class="bg-[#3C2A21]/5 text-[#3C2A21]/70 font-heading text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-8 py-5">Kode & Acara / Matkul</th>
                        <th class="px-8 py-5">Ruangan</th>
                        <th class="px-8 py-5">Dosen / Pemohon</th>
                        <th class="px-8 py-5">Jadwal Pemakaian</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#3C2A21]/5 bg-transparent">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-white/40 transition-colors">
                            <td class="px-8 py-6">
                                <span class="font-heading font-mono text-xs font-bold text-[#A1553E] block">{{ $booking->booking_code }}</span>
                                <span class="font-heading font-bold text-base text-[#271A15] mt-1 block">{{ $booking->title }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="font-heading font-bold text-sm text-[#271A15] block">{{ $booking->room->name }}</span>
                                <span class="font-heading text-xs text-[#3C2A21]/60 mt-0.5 block">{{ $booking->room->code }} &bull; {{ $booking->room->location }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="font-heading font-bold text-sm text-[#271A15] block">{{ $booking->borrower_name }}</span>
                                <span class="font-mono text-xs text-[#3C2A21]/70 block">NIP/NIM: {{ $booking->borrower_nim }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="font-heading font-bold text-sm text-[#271A15] block">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('d M Y') }}
                                </span>
                                <span class="font-heading text-xs text-[#3C2A21]/70 block mt-0.5">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $isOngoing = in_array($booking->status, ['confirmed', 'checked_in']) && $now->between($booking->start_time, $booking->end_time);
                                    if ($isOngoing) {
                                        $statusStyle = 'bg-rose-500/10 text-rose-950 border-rose-500/20';
                                        $statusLabel = 'Sedang Berlangsung';
                                    } elseif ($booking->status === 'pending') {
                                        $statusStyle = 'bg-amber-500/10 text-amber-900 border-amber-500/20';
                                        $statusLabel = 'Menunggu Persetujuan';
                                    } elseif ($booking->status === 'confirmed') {
                                        $statusStyle = 'bg-emerald-500/10 text-emerald-950 border-emerald-500/20';
                                        $statusLabel = 'Terjadwal / Disetujui';
                                    } elseif ($booking->status === 'checked_in') {
                                        $statusStyle = 'bg-sky-500/10 text-sky-950 border-sky-500/20';
                                        $statusLabel = 'Aktif Digunakan';
                                    } elseif ($booking->status === 'rejected') {
                                        $statusStyle = 'bg-rose-500/10 text-rose-900 border-rose-500/20';
                                        $statusLabel = 'Ditolak';
                                    } else {
                                        $statusStyle = 'bg-stone-500/10 text-stone-800 border-stone-500/20';
                                        $statusLabel = 'Selesai';
                                    }
                                @endphp
                                <span class="px-3.5 py-1.5 rounded-xl font-heading text-xs font-bold tracking-wide uppercase border {{ $statusStyle }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <button
                                    type="button"
                                    wire:click="showDetail({{ $booking->id }})"
                                    class="inline-flex items-center px-4 py-2 rounded-xl font-heading text-xs font-bold glossy-button-secondary shadow-xs hover:border-[#3C2A21]/30 transition-all"
                                >
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center font-body text-sm text-[#3C2A21]/60">
                                Tidak ada data jadwal pemakaian yang sesuai dengan pencarian atau filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="block md:hidden divide-y divide-[#3C2A21]/10">
            @forelse($bookings as $booking)
                <div class="p-6 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="font-heading font-mono text-xs font-bold text-[#A1553E]">{{ $booking->booking_code }}</span>
                            <h3 class="font-heading font-bold text-base text-[#271A15] mt-0.5">{{ $booking->title }}</h3>
                        </div>
                        @php
                            $isOngoing = in_array($booking->status, ['confirmed', 'checked_in']) && $now->between($booking->start_time, $booking->end_time);
                            if ($isOngoing) {
                                $statusStyle = 'bg-rose-500/10 text-rose-950 border-rose-500/20';
                                $statusLabel = 'Berlangsung';
                            } elseif ($booking->status === 'pending') {
                                $statusStyle = 'bg-amber-500/10 text-amber-900 border-amber-500/20';
                                $statusLabel = 'Menunggu';
                            } elseif ($booking->status === 'confirmed') {
                                $statusStyle = 'bg-emerald-500/10 text-emerald-950 border-emerald-500/20';
                                $statusLabel = 'Terjadwal';
                            } elseif ($booking->status === 'checked_in') {
                                $statusStyle = 'bg-sky-500/10 text-sky-950 border-sky-500/20';
                                $statusLabel = 'Aktif';
                            } elseif ($booking->status === 'rejected') {
                                $statusStyle = 'bg-rose-500/10 text-rose-900 border-rose-500/20';
                                $statusLabel = 'Ditolak';
                            } else {
                                $statusStyle = 'bg-stone-500/10 text-stone-800 border-stone-500/20';
                                $statusLabel = 'Selesai';
                            }
                        @endphp
                        <span class="px-3 py-1 rounded-xl font-heading text-[11px] font-bold tracking-wide uppercase border {{ $statusStyle }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="text-xs font-heading space-y-1 bg-white/40 p-3.5 rounded-xl border border-white/60">
                        <div class="flex justify-between">
                            <span class="text-[#3C2A21]/60">Ruangan</span>
                            <span class="font-bold text-[#271A15]">{{ $booking->room->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#3C2A21]/60">Dosen/PIC</span>
                            <span class="font-bold text-[#271A15]">{{ $booking->borrower_name }} ({{ $booking->borrower_nim }})</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#3C2A21]/60">Jadwal</span>
                            <span class="font-bold text-[#271A15]">
                                {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('d M Y') }} &bull; {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-1 flex justify-end">
                        <button
                            type="button"
                            wire:click="showDetail({{ $booking->id }})"
                            class="w-full py-2.5 rounded-xl font-heading text-xs font-bold glossy-button-secondary text-center"
                        >
                            Lihat Rincian Jadwal
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center font-body text-sm text-[#3C2A21]/60">
                    Tidak ada data jadwal pemakaian yang sesuai.
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">
        {{ $bookings->links() }}
    </div>

    @if($selectedBooking)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-[#271A15]/60 backdrop-blur-md flex items-center justify-center p-4">
            <div class="glass-card bg-[#FAF7F2]/98 rounded-3xl max-w-lg w-full p-8 sm:p-10 shadow-2xl border border-white/90">
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-[#3C2A21]/10">
                    <div>
                        <span class="font-heading font-mono text-xs font-bold text-[#A1553E]">{{ $selectedBooking->booking_code }}</span>
                        <h3 class="font-heading text-2xl font-bold text-[#271A15] mt-0.5">Rincian Jadwal Pemakaian</h3>
                    </div>
                    <button wire:click="closeDetail" class="p-2 rounded-xl text-[#3C2A21]/60 hover:text-[#271A15] hover:bg-black/5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 text-xs font-heading">
                    <div class="p-4 rounded-2xl bg-white/70 border border-[#3C2A21]/5 space-y-2">
                        <span class="text-[#3C2A21]/60 uppercase tracking-wider block text-[11px]">Agenda / Mata Kuliah</span>
                        <span class="text-base font-bold text-[#271A15] block">{{ $selectedBooking->title }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3.5 rounded-xl bg-white/50 border border-[#3C2A21]/5">
                            <span class="text-[#3C2A21]/60 uppercase tracking-wider block text-[10px] mb-1">Ruangan</span>
                            <span class="font-bold text-[#271A15] block text-sm">{{ $selectedBooking->room->name }}</span>
                            <span class="text-[11px] text-[#3C2A21]/60 mt-0.5 block">{{ $selectedBooking->room->code }}</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-white/50 border border-[#3C2A21]/5">
                            <span class="text-[#3C2A21]/60 uppercase tracking-wider block text-[10px] mb-1">Lokasi Gedung</span>
                            <span class="font-bold text-[#271A15] block text-sm">{{ $selectedBooking->room->location }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3.5 rounded-xl bg-white/50 border border-[#3C2A21]/5">
                            <span class="text-[#3C2A21]/60 uppercase tracking-wider block text-[10px] mb-1">Dosen / Penanggung Jawab</span>
                            <span class="font-bold text-[#271A15] block text-sm">{{ $selectedBooking->borrower_name }}</span>
                            <span class="text-[11px] text-[#3C2A21]/60 mt-0.5 block">NIP/NIM: {{ $selectedBooking->borrower_nim }}</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-white/50 border border-[#3C2A21]/5">
                            <span class="text-[#3C2A21]/60 uppercase tracking-wider block text-[10px] mb-1">Kontak WhatsApp</span>
                            <span class="font-bold text-[#271A15] block text-sm">{{ $selectedBooking->borrower_phone }}</span>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-white/50 border border-[#3C2A21]/5">
                        <span class="text-[#3C2A21]/60 uppercase tracking-wider block text-[10px] mb-1">Waktu Pelaksanaan</span>
                        <span class="font-bold text-sm text-[#271A15] block">
                            {{ \Carbon\Carbon::parse($selectedBooking->start_time)->translatedFormat('l, d F Y') }}
                        </span>
                        <span class="text-xs text-[#3C2A21]/70 block mt-0.5">
                            Pukul {{ \Carbon\Carbon::parse($selectedBooking->start_time)->format('H:i') }} s/d {{ \Carbon\Carbon::parse($selectedBooking->end_time)->format('H:i') }} WIB
                        </span>
                    </div>

                    @if($selectedBooking->purpose)
                        <div class="p-3.5 rounded-xl bg-white/50 border border-[#3C2A21]/5">
                            <span class="text-[#3C2A21]/60 uppercase tracking-wider block text-[10px] mb-1">Keterangan Tambahan</span>
                            <span class="font-body text-xs text-[#3C2A21]/80 block leading-relaxed">{{ $selectedBooking->purpose }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-8 pt-4 border-t border-[#3C2A21]/10 flex justify-end">
                    <button
                        type="button"
                        wire:click="closeDetail"
                        class="px-6 py-2.5 rounded-xl font-heading text-xs font-bold glossy-button-secondary"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
