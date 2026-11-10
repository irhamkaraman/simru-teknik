<?php

use App\Livewire\RoomBrowser;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Services\BookingService;
use App\Services\QrCodeService;
use App\Services\RoomService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('qr code service generates valid svg output', function () {
    $qrService = app(QrCodeService::class);
    $svg = $qrService->generateSvg('https://example.com/test-qr');

    expect($svg)->toBeString()
        ->toContain('<svg')
        ->toContain('</svg>');
});

test('booking service prevents overlapping bookings', function () {
    $user = User::factory()->create();
    $room = Room::create([
        'name' => 'Lab Uji Coba',
        'code' => 'LAB-TEST-01',
        'slug' => 'lab-uji-coba',
        'capacity' => 20,
        'location' => 'Lt. 1',
        'status' => 'available',
        'qr_identifier' => 'test-identifier-123',
    ]);

    $service = app(BookingService::class);

    $start = Carbon::today()->setTime(10, 0);
    $end = Carbon::today()->setTime(12, 0);

    $booking = $service->createBooking([
        'user_id' => $user->id,
        'room_id' => $room->id,
        'borrower_name' => 'Budi Santoso',
        'borrower_nim' => '22041001',
        'borrower_phone' => '081234567890',
        'title' => 'Rapat Pengujian Sistem',
        'start_time' => $start,
        'end_time' => $end,
        'status' => 'confirmed',
    ]);

    expect($booking)->toBeInstanceOf(Booking::class)
        ->and($booking->status)->toBe('confirmed');

    $isAvailable = $service->isSlotAvailable($room->id, $start->copy()->addMinutes(30), $end->copy()->addMinutes(30));
    expect($isAvailable)->toBeFalse();

    $this->expectException(InvalidArgumentException::class);
    $service->createBooking([
        'user_id' => $user->id,
        'room_id' => $room->id,
        'borrower_name' => 'Bentrok User',
        'borrower_nim' => '22041002',
        'borrower_phone' => '081234567891',
        'title' => 'Rapat Bentrok',
        'start_time' => $start->copy()->addMinutes(30),
        'end_time' => $end->copy()->addMinutes(30),
    ]);
});

test('room service evaluates room availability accurately', function () {
    $room = Room::create([
        'name' => 'Ruang Akurasi',
        'code' => 'ACC-01',
        'slug' => 'ruang-akurasi',
        'capacity' => 15,
        'location' => 'Lt. 2',
        'status' => 'available',
        'qr_identifier' => 'acc-room-qr',
    ]);

    $roomService = app(RoomService::class);
    $status = $roomService->getRoomCurrentStatus($room);

    expect($status['state'])->toBe('available');
});

test('room service correctly identifies maintenance status and blocks booking', function () {
    $room = Room::create([
        'name' => 'Lab Sedang Rusak',
        'code' => 'LAB-RSK-01',
        'slug' => 'lab-sedang-rusak',
        'capacity' => 15,
        'location' => 'Lt. 1',
        'status' => 'maintenance',
        'qr_identifier' => 'lab-rsk-qr',
    ]);

    $roomService = app(RoomService::class);
    $status = $roomService->getRoomCurrentStatus($room);

    expect($status['state'])->toBe('maintenance')
        ->and($status['label'])->toBe('Sedang Pemeliharaan');

    $bookingService = app(BookingService::class);
    $isAvailable = $bookingService->isSlotAvailable($room->id, Carbon::now()->addHour(), Carbon::now()->addHours(2));
    expect($isAvailable)->toBeFalse();
});

test('room service automatically marks room as occupied when current time falls within schedule range', function () {
    $room = Room::create([
        'name' => 'Lab Kuliah Aktif',
        'code' => 'LAB-ACT-01',
        'slug' => 'lab-kuliah-aktif',
        'capacity' => 30,
        'location' => 'Lt. 3',
        'status' => 'available',
        'qr_identifier' => 'lab-act-qr',
    ]);

    Booking::create([
        'booking_code' => 'JDW-TEST-NOW',
        'room_id' => $room->id,
        'borrower_name' => 'Dosen Pengampu',
        'borrower_nim' => 'DOSEN001',
        'borrower_phone' => '081234567890',
        'title' => 'Kuliah Pemrograman Web',
        'start_time' => Carbon::now()->subMinutes(30),
        'end_time' => Carbon::now()->addMinutes(30),
        'status' => 'confirmed',
    ]);

    $roomService = app(RoomService::class);
    $status = $roomService->getRoomCurrentStatus($room);

    expect($status['state'])->toBe('occupied')
        ->and($status['label'])->toBe('Terpakai (Sedang Digunakan)');
});

test('root route redirects to rooms index', function () {
    $response = $this->get('/');
    $response->assertRedirect('/rooms');
});

test('rooms index page renders successfully with livewire', function () {
    $room = Room::create([
        'name' => 'Lab Fisika Terapan',
        'code' => 'LAB-FIS-01',
        'slug' => 'lab-fisika-terapan',
        'capacity' => 25,
        'location' => 'Lt. 3',
        'status' => 'available',
        'qr_identifier' => 'fisika-terapan-qr',
    ]);

    $response = $this->get('/rooms');
    $response->assertStatus(200);
    $response->assertSee('Lab Fisika Terapan');
});

test('room browser livewire component loads 9 rooms and expands on load more', function () {
    for ($i = 1; $i <= 12; $i++) {
        Room::create([
            'name' => "Lab Percobaan {$i}",
            'code' => "LAB-EXP-{$i}",
            'slug' => "lab-percobaan-{$i}",
            'capacity' => 20,
            'location' => 'Gedung Percobaan',
            'status' => 'available',
            'qr_identifier' => "room-exp-qr-{$i}",
        ]);
    }

    Livewire::test(RoomBrowser::class)
        ->assertSet('perPage', 9)
        ->assertSee('Lab Percobaan 1')
        ->assertSee('Tampilkan 9 Ruangan Lagi')
        ->call('loadMore')
        ->assertSet('perPage', 18)
        ->assertSee('Lab Percobaan 12');
});

test('qr room door identifier redirects to room detail page with slug', function () {
    $room = Room::create([
        'name' => 'Lab Elektronika Dasar',
        'code' => 'LAB-EL-01',
        'slug' => 'lab-elektronika-dasar',
        'capacity' => 30,
        'location' => 'Gedung E Lt. 1',
        'status' => 'available',
        'qr_identifier' => 'lab-el-dasar-door',
    ]);

    $response = $this->get('/qr/room/lab-el-dasar-door');
    $response->assertRedirect(route('rooms.show', $room->slug));
});

test('room detail renders using slug URL without bullet dots', function () {
    $room = Room::create([
        'name' => 'Studio Robotika Lanjutan',
        'code' => 'ROB-ADV-01',
        'slug' => 'studio-robotika-lanjutan',
        'capacity' => 18,
        'location' => 'Lt. 2 Gedung Riset',
        'status' => 'available',
        'facilities' => ['3D Scanner', 'Robot Arm'],
        'qr_identifier' => 'rob-adv-door-qr',
    ]);

    $response = $this->get(route('rooms.show', $room->slug));
    $response->assertStatus(200);
    $response->assertSee('Studio Robotika Lanjutan');
    $response->assertSee('3D Scanner');
});

test('filament admin login page renders successfully', function () {
    $response = $this->get('/admin/login');
    $response->assertStatus(200);
});

test('filament admin dashboard accessible by authenticated admin', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->get('/admin');
    $response->assertStatus(200);
});
