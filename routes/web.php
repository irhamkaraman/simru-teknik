<?php

use App\Livewire\BookingForm;
use App\Livewire\BookingTicket;
use App\Livewire\MyBookings;
use App\Livewire\QrScanner;
use App\Livewire\RoomBrowser;
use App\Livewire\RoomDetail;
use App\Models\Room;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('rooms.index'));

Route::get('/rooms', RoomBrowser::class)->name('rooms.index');
Route::get('/rooms/{slug}', RoomDetail::class)->name('rooms.show');

Route::get('/qr/room/{identifier}', function (string $identifier) {
    $room = Room::where('qr_identifier', $identifier)->firstOrFail();

    return redirect()->route('rooms.show', $room->slug);
})->name('rooms.qr');

Route::get('/bookings', MyBookings::class)->name('bookings.index');
Route::get('/bookings/create', BookingForm::class)->name('bookings.create');
Route::get('/bookings/ticket/{code}', BookingTicket::class)->name('bookings.ticket');

Route::get('/qr-scanner', QrScanner::class)->name('qr.scanner');
