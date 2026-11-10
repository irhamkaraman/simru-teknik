# SIMRU Teknik - Smart Room Booking System Fakultas Teknik

Sistem Informasi Manajemen Ruangan (SIMRU Teknik) adalah platform berbasis web modern untuk pengelolaan, reservasi, dan monitoring penggunaan ruangan/laboratorium di lingkungan Fakultas Teknik. Sistem ini mengintegrasikan antarmuka reservasi publik berbasis Livewire dan panel administrasi cerdas berbasis Filament PHP.

---

## Fitur Utama

- **Katalog & Penelusuran Ruangan Real-time**: Menampilkan ketersediaan ruangan secara interaktif dengan indikator visual (*Tersedia*, *Akan Digunakan Segera*, *Sedang Digunakan*, atau *Pemeliharaan*).
- **Pencegahan Tabrakan Jadwal (Conflict-Free Booking)**: Engine validasi otomatis yang mencegah reservasi bertabrakan (*overlapping slots*) pada ruangan yang sama.
- **Sistem QR Code Terintegrasi**:
  - **QR Code Pintu Ruangan**: Stiker QR pada pintu fisik untuk akses cepat langsung ke halaman detail & reservasi ruangan.
  - **E-Ticket Reservasi**: Tiket digital ber-QR code untuk bukti peminjaman dan verifikasi check-in/check-out.
- **Audit Log & Siklus Hidup Peminjaman**: Pelacakan status transparan (`pending` -> `confirmed` -> `checked_in` -> `completed` / `cancelled` / `rejected`) lengkap dengan riwayat log aktivitas.
- **Filament Admin Panel**:
  - Dashboard analitik peminjaman & statistik utilitas ruangan.
  - Manajemen master data ruangan, fasilitas, kapasitas, dan identifikasi QR.
  - Manajemen persetujuan (*approval/rejection*) peminjaman ruangan.
  - Manajemen akun dan hak akses pengguna.
- **Zero-Comment Clean Architecture**: Mengadopsi prinsip Single Responsibility & Separation of Concerns (Presentation Layer, Service Layer, dan Data Layer) dengan penulisan kode yang deklaratif dan *type-safe*.

---

## Arsitektur Aplikasi

```
simru-teknik/
├── app/
│   ├── Filament/                # Panel Admin (Resources, Tables, Widgets)
│   │   ├── Resources/
│   │   │   ├── Bookings/        # Manajemen Peminjaman
│   │   │   ├── Rooms/           # Manajemen Ruangan & Cetak QR
│   │   │   ├── Schedules/       # Penjadwalan Ruangan
│   │   │   └── Users/           # Manajemen Pengguna
│   │   └── Widgets/             # Dashboard Widgets & Charts
│   ├── Livewire/                # Front-end Reactive Components
│   │   ├── BookingForm.php      # Formulir Pengajuan Peminjaman
│   │   ├── BookingTicket.php    # Tiket Digital Peminjaman
│   │   ├── MyBookings.php       # Riwayat & Status Peminjaman Pengguna
│   │   ├── QrScanner.php        # Pemindai QR Terintegrasi
│   │   ├── RoomBrowser.php      # Eksplorasi Ruangan (Filter & Pencarian)
│   │   └── RoomDetail.php       # Detail Ruangan & Kalender Jadwal
│   ├── Models/                  # Eloquent Models (Strict Casts & Scopes)
│   │   ├── Booking.php
│   │   ├── BookingLog.php
│   │   ├── Room.php
│   │   └── User.php
│   └── Services/                # Domain Business Logic Layer
│       ├── BookingService.php   # Validasi overlap, alur status, booking code
│       ├── QrCodeService.php    # Generator SVG/Data-URI & verifikasi QR
│       └── RoomService.php      # Logika status aktif, jadwal harian & filter
├── database/
│   └── migrations/              # Skema tabel users, rooms, bookings, logs
├── resources/
│   └── views/                   # Blade templates & atomic UI components
└── tests/                       # Pest & PHPUnit Feature/Unit test suites
```

---

## Kebutuhan Sistem

- **PHP**: ^8.3
- **Database**: MySQL 8.0+ / MariaDB 10.5+ / PostgreSQL
- **Composer**: 2.x
- **Node.js & NPM**: Node 18+

---

## Panduan Instalasi

1. **Clone Repositori**:
   ```bash
   git clone https://github.com/irhamkaraman/simru-teknik.git
   cd simru-teknik
   ```

2. **Pasang Dependensi PHP**:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Sesuaikan konfigurasi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) pada file `.env`.*

4. **Migrasi & Seeding Database**:
   ```bash
   php artisan migrate --seed
   ```

5. **Pasang Dependensi Frontend & Kompilasi**:
   ```bash
   npm install
   npm run build
   ```

6. **Jalankan Aplikasi Development**:
   ```bash
   php artisan serve
   ```
   - Antarmuka Publik: `http://127.0.0.1:8000`
   - Panel Admin Filament: `http://127.0.0.1:8000/admin`

---

## Standar Kode & Pengujian

- **Formatting (Laravel Pint)**:
  ```bash
  vendor/bin/pint --format agent
  ```
- **Pengujian (Pest Framework)**:
  ```bash
  php artisan test
  # atau
  vendor/bin/pest
  ```

---

## Lisensi

Aplikasi ini dikembangkan untuk lingkungan Fakultas Teknik dan dilisensikan di bawah lisensi [MIT](LICENSE).
