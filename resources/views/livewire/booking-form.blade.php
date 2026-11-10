<div class="max-w-4xl mx-auto">
    <div class="mb-8 sm:mb-10">
        <a href="{{ route('rooms.index') }}" class="inline-flex items-center font-heading text-sm font-semibold text-[#3C2A21]/70 hover:text-[#271A15] transition-colors mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Ruangan
        </a>
        <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#271A15] tracking-tight leading-tight">
            Formulir Reservasi Ruangan
        </h1>
        <p class="font-body text-base text-[#3C2A21]/80 mt-2">
            Isi identitas pemohon dan jadwal pemakaian. Permohonan Anda akan diverifikasi oleh Admin Lab Teknik.
        </p>
    </div>

    <div class="glass-card rounded-3xl p-8 sm:p-12">
        <form wire:submit="submit" class="space-y-8">
            <div class="relative z-30">
                <label for="room_id" class="block font-heading text-sm font-bold text-[#271A15] mb-2 uppercase tracking-wide">
                    Pilih Ruangan / Laboratorium <span class="text-[#A1553E]">*</span>
                </label>
                <x-dropdown-select
                    id="room_id"
                    wire:model.live="room_id"
                    placeholder="-- Silakan Pilih Ruangan --"
                    :options="$roomOptions"
                />
                @error('room_id') <span class="font-heading text-xs text-rose-700 mt-2 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="booking_date" class="block font-heading text-sm font-bold text-[#271A15] mb-2 uppercase tracking-wide">
                        Tanggal <span class="text-[#A1553E]">*</span>
                    </label>
                    <input
                        id="booking_date"
                        type="date"
                        wire:model.live="booking_date"
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3.5 rounded-2xl glass-input font-heading text-sm text-[#271A15] focus:outline-none"
                    />
                    @error('booking_date') <span class="font-heading text-xs text-rose-700 mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="start_time" class="block font-heading text-sm font-bold text-[#271A15] mb-2 uppercase tracking-wide">
                        Waktu Mulai <span class="text-[#A1553E]">*</span>
                    </label>
                    <input
                        id="start_time"
                        type="time"
                        wire:model.live="start_time"
                        class="w-full px-4 py-3.5 rounded-2xl glass-input font-heading text-sm text-[#271A15] focus:outline-none"
                    />
                    @error('start_time') <span class="font-heading text-xs text-rose-700 mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="end_time" class="block font-heading text-sm font-bold text-[#271A15] mb-2 uppercase tracking-wide">
                        Waktu Selesai <span class="text-[#A1553E]">*</span>
                    </label>
                    <input
                        id="end_time"
                        type="time"
                        wire:model.live="end_time"
                        class="w-full px-4 py-3.5 rounded-2xl glass-input font-heading text-sm text-[#271A15] focus:outline-none"
                    />
                    @error('end_time') <span class="font-heading text-xs text-rose-700 mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>

            @if($isAvailable !== null)
                <div class="p-5 rounded-2xl border backdrop-blur-md font-heading text-sm flex items-center space-x-3.5 {{ $isAvailable ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-950' : 'bg-[#A1553E]/10 border-[#A1553E]/20 text-[#4A2418]' }}">
                    <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $isAvailable ? 'bg-emerald-600' : 'bg-[#A1553E]' }}"></span>
                    <span class="font-semibold">{{ $availabilityMessage }}</span>
                </div>
            @endif

            <div class="pt-6 border-t border-[#3C2A21]/10">
                <h3 class="font-heading text-lg font-bold text-[#271A15] mb-4">Data Pemohon (Mahasiswa / Dosen)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div x-data="{ len: 0 }" x-init="len = ($wire.borrower_name || '').length; $watch('$wire.borrower_name', val => len = (val || '').length)">
                        <label for="borrower_name" class="block font-heading text-sm font-bold text-[#271A15] mb-2 uppercase tracking-wide">
                            Nama Lengkap <span class="text-[#A1553E]">*</span>
                        </label>
                        <input
                            id="borrower_name"
                            type="text"
                            maxlength="100"
                            wire:model="borrower_name"
                            placeholder="Contoh: Budi Santoso"
                            class="w-full px-4 py-3.5 rounded-2xl glass-input font-heading text-sm text-[#271A15] placeholder-[#3C2A21]/40 focus:outline-none"
                        />
                        <div class="flex justify-between items-center mt-1.5 px-1">
                            <div>
                                @error('borrower_name') <span class="font-heading text-xs text-rose-700 block">{{ $message }}</span> @enderror
                            </div>
                            <span class="text-[11px] text-[#3C2A21]/50 font-mono ml-auto" x-text="len + '/100'"></span>
                        </div>
                    </div>

                    <div x-data="{ len: 0 }" x-init="len = ($wire.borrower_nim || '').length; $watch('$wire.borrower_nim', val => len = (val || '').length)">
                        <label for="borrower_nim" class="block font-heading text-sm font-bold text-[#271A15] mb-2 uppercase tracking-wide">
                            NIM / NIP <span class="text-[#A1553E]">*</span>
                        </label>
                        <input
                            id="borrower_nim"
                            type="text"
                            maxlength="8"
                            wire:model="borrower_nim"
                            placeholder="Contoh: 22041001"
                            class="w-full px-4 py-3.5 rounded-2xl glass-input font-heading font-mono text-sm text-[#271A15] placeholder-[#3C2A21]/40 focus:outline-none"
                        />
                        <div class="flex justify-between items-center mt-1.5 px-1">
                            <div>
                                @error('borrower_nim') <span class="font-heading text-xs text-rose-700 block">{{ $message }}</span> @enderror
                            </div>
                            <span class="text-[11px] text-[#3C2A21]/50 font-mono ml-auto" x-text="len + '/8'"></span>
                        </div>
                    </div>

                    <div x-data="{ len: 0 }" x-init="len = ($wire.borrower_phone || '').length; $watch('$wire.borrower_phone', val => len = (val || '').length)">
                        <label for="borrower_phone" class="block font-heading text-sm font-bold text-[#271A15] mb-2 uppercase tracking-wide">
                            No. WhatsApp Aktif <span class="text-[#A1553E]">*</span>
                        </label>
                        <input
                            id="borrower_phone"
                            type="text"
                            maxlength="16"
                            wire:model="borrower_phone"
                            placeholder="Contoh: 081234567890"
                            class="w-full px-4 py-3.5 rounded-2xl glass-input font-heading text-sm text-[#271A15] placeholder-[#3C2A21]/40 focus:outline-none"
                        />
                        <div class="flex justify-between items-center mt-1.5 px-1">
                            <div>
                                @error('borrower_phone') <span class="font-heading text-xs text-rose-700 block">{{ $message }}</span> @enderror
                            </div>
                            <span class="text-[11px] text-[#3C2A21]/50 font-mono ml-auto" x-text="len + '/16'"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-[#3C2A21]/10 space-y-6">
                <div x-data="{ countWords(str) { return str && str.trim() ? str.trim().split(/\s+/).length : 0; } }">
                    <label for="title" class="block font-heading text-sm font-bold text-[#271A15] mb-2 uppercase tracking-wide">
                        Nama Kegiatan / Agenda Acara <span class="text-[#A1553E]">*</span>
                    </label>
                    <input
                        id="title"
                        type="text"
                        wire:model="title"
                        placeholder="Contoh: Praktikum Pemrograman Kendali Robot Industri"
                        class="w-full px-4 py-3.5 rounded-2xl glass-input font-heading text-sm text-[#271A15] placeholder-[#3C2A21]/40 focus:outline-none"
                    />
                    <div class="flex justify-between items-center mt-1.5 px-1">
                        <div>
                            @error('title') <span class="font-heading text-xs text-rose-700 block">{{ $message }}</span> @enderror
                        </div>
                        <span class="text-[11px] text-[#3C2A21]/50 font-mono ml-auto" x-text="countWords($wire.title || '') + '/100 kata'"></span>
                    </div>
                </div>

                <div x-data="{ countWords(str) { return str && str.trim() ? str.trim().split(/\s+/).length : 0; } }">
                    <label for="purpose" class="block font-heading text-sm font-bold text-[#271A15] mb-2 uppercase tracking-wide">
                        Keperluan & Catatan
                    </label>
                    <textarea
                        id="purpose"
                        wire:model="purpose"
                        rows="3"
                        placeholder="Rincian peralatan tambahan, jumlah peserta perkiraan, atau kontak penanggung jawab..."
                        class="w-full px-4 py-3.5 rounded-2xl glass-input font-body text-sm text-[#271A15] placeholder-[#3C2A21]/40 focus:outline-none"
                    ></textarea>
                    <div class="flex justify-between items-center mt-1.5 px-1">
                        <div>
                            @error('purpose') <span class="font-heading text-xs text-rose-700 block">{{ $message }}</span> @enderror
                        </div>
                        <span class="text-[11px] text-[#3C2A21]/50 font-mono ml-auto" x-text="countWords($wire.purpose || '') + '/500 kata'"></span>
                    </div>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-[#3C2A21]/5 text-xs font-body text-[#3C2A21]/80 space-y-1">
                <span class="font-heading font-bold text-[#271A15] block mb-1">Catatan Alur Persetujuan:</span>
                <p>Setelah formulir diajukan, status reservasi Anda adalah <strong>Menunggu Persetujuan (Pending)</strong>. Admin Lab Teknik akan meninjau dan memberikan persetujuan sebelum ruangan dapat digunakan.</p>
            </div>

            <div class="pt-6 border-t border-[#3C2A21]/10 flex flex-col sm:flex-row items-center justify-end gap-4">
                <a
                    href="{{ route('rooms.index') }}"
                    class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-heading text-sm font-semibold text-center glossy-button-secondary"
                >
                    Batalkan
                </a>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    @if($isAvailable === false) disabled @endif
                    class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-heading text-sm font-bold text-center glossy-button-primary shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove>Ajukan Permohonan Reservasi</span>
                    <span wire:loading>Memproses Pengajuan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
