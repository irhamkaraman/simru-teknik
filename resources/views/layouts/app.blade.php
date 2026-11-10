<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SIMRU Teknik - Universitas Muhammadiyah Ponorogo' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <script src="{{ asset('assets/js/tailwind.js') }}"></script>
    @livewireStyles
</head>
<body class="min-h-full flex flex-col antialiased selection:bg-[#A1553E] selection:text-white">
    <x-navbar />

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-28 md:pb-12 md:pr-24">
        <div class="hidden md:flex items-center justify-between pb-6 mb-8 border-b border-[#3C2A21]/10">
            <a href="{{ route('rooms.index') }}" class="inline-flex items-center space-x-3.5 group">
                <img
                    src="{{ asset('assets/images/logo.svg') }}"
                    alt="SIMRU Teknik Logo Merak"
                    class="w-12 h-12 rounded-2xl shadow-md object-contain transition-transform duration-300 group-hover:scale-105"
                />
                <div>
                    <span class="text-2xl font-heading font-extrabold text-[#271A15] tracking-tight block leading-tight">
                        SIMRU<span class="text-[#A1553E] font-medium ml-1">Teknik</span>
                    </span>
                    <span class="text-xs uppercase tracking-widest text-[#3C2A21]/60 font-heading block">
                        Fakultas Teknik Universitas Muhammadiyah Ponorogo
                    </span>
                </div>
            </a>
            <div class="text-right">
                <span class="text-xs font-heading font-semibold text-[#A1553E] block">Sistem Manajemen Ruangan Lab</span>
                <span class="text-[11px] font-body text-[#3C2A21]/60 block">Gedung F Universitas Muhammadiyah Ponorogo</span>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8">
                <x-alert type="success">
                    <span class="font-medium text-base">{{ session('success') }}</span>
                </x-alert>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8">
                <x-alert type="danger">
                    <span class="font-medium text-base">{{ session('error') }}</span>
                </x-alert>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="mt-auto border-t border-[#3C2A21]/10 bg-gradient-to-b from-white/40 to-[#FAF7F2]/95 backdrop-blur-xl pt-14 pb-28 md:pb-12 md:pr-24 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 pb-12 border-b border-[#3C2A21]/10">
                <div class="md:col-span-5 space-y-4">
                    <div class="flex items-center space-x-3.5">
                        <img
                            src="{{ asset('assets/images/logo.svg') }}"
                            alt="Logo Merak SIMRU Teknik"
                            class="w-12 h-12 rounded-2xl shadow-md object-contain"
                        />
                        <div>
                            <span class="text-2xl font-heading font-extrabold text-[#271A15] tracking-tight block leading-tight">
                                SIMRU<span class="text-[#A1553E] font-medium ml-1">Teknik</span>
                            </span>
                            <span class="text-[10px] uppercase tracking-widest text-[#3C2A21]/60 font-heading font-bold block">
                                Fakultas Teknik UMPO
                            </span>
                        </div>
                    </div>
                    <p class="font-heading text-sm font-bold text-[#271A15] leading-snug">
                        Sistem Manajemen Ruangan Lab Fakultas Teknik Universitas Muhammadiyah Ponorogo
                    </p>
                    <p class="font-body text-xs text-[#3C2A21]/75 leading-relaxed">
                        Layanan informasi ketersediaan jadwal perkuliahan, praktikum, dan peminjaman fasilitas laboratorium di lingkungan Fakultas Teknik.
                    </p>
                </div>

                <div class="md:col-span-4 space-y-3">
                    <span class="font-heading text-xs font-bold uppercase tracking-wider text-[#A1553E] block">
                        Lokasi & Kampus
                    </span>
                    <h4 class="font-heading text-base font-bold text-[#271A15]">
                        Gedung F Universitas Muhammadiyah Ponorogo
                    </h4>
                    <p class="font-body text-xs text-[#3C2A21]/80 leading-relaxed flex items-start space-x-2">
                        <svg class="w-4 h-4 text-[#A1553E] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Jalan Budi Utomo No 10 Ronowijawan, Kec. Siman, Kab. Ponorogo, Jawa Timur 63471</span>
                    </p>
                    <div class="pt-1">
                        <span class="text-[11px] font-heading font-semibold text-[#3C2A21]/60 block">Jam Operasional Lab:</span>
                        <span class="text-xs font-heading font-bold text-[#271A15]">Senin - Jumat &bull; 07.30 - 16.00 WIB</span>
                    </div>
                </div>

                <div class="md:col-span-3 space-y-3">
                    <span class="font-heading text-xs font-bold uppercase tracking-wider text-[#A1553E] block">
                        Pintasan Cepat
                    </span>
                    <ul class="space-y-2 text-xs font-heading font-semibold text-[#3C2A21]/80">
                        <li>
                            <a href="{{ route('rooms.index') }}" class="hover:text-[#A1553E] transition-colors flex items-center space-x-1.5">
                                <span>&bull;</span>
                                <span>Katalog Ruangan & Lab</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bookings.index') }}" class="hover:text-[#A1553E] transition-colors flex items-center space-x-1.5">
                                <span>&bull;</span>
                                <span>Jadwal Pemakaian Ruang</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bookings.create') }}" class="hover:text-[#A1553E] transition-colors flex items-center space-x-1.5">
                                <span>&bull;</span>
                                <span>Formulir Peminjaman</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('qr.scanner') }}" class="hover:text-[#A1553E] transition-colors flex items-center space-x-1.5">
                                <span>&bull;</span>
                                <span>Layanan E-Tiket & Cek ID</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-[#3C2A21]/60 gap-3 font-heading">
                <div>
                    &copy; {{ date('Y') }} Fakultas Teknik Universitas Muhammadiyah Ponorogo. Seluruh hak cipta dilindungi.
                </div>
                <div class="flex items-center space-x-4 text-[11px] uppercase tracking-wider text-[#3C2A21]/50 font-bold">
                    <span>SIMRU Teknik</span>
                    <span>&bull;</span>
                    <span>Gedung F UMPO</span>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
