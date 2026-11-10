<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Room;
use App\Services\QrCodeService;
use Livewire\Component;

class QrScanner extends Component
{
    public string $tab = 'checkin';

    public string $bookingCodeInput = '';

    public string $roomIdentifierInput = '';

    public ?Booking $resolvedBooking = null;

    public ?Room $resolvedRoom = null;

    public string $scanMessage = '';

    public string $scanStatus = '';

    public function processBookingCode(QrCodeService $qrService)
    {
        $this->resetScanState();

        $code = trim($this->bookingCodeInput);
        if (empty($code)) {
            $this->scanStatus = 'error';
            $this->scanMessage = 'Silakan masukkan kode reservasi e-tiket.';

            return;
        }

        if (str_contains($code, '/ticket/')) {
            $parts = explode('/ticket/', $code);
            $code = end($parts);
        }

        $booking = $qrService->resolveBooking($code);

        if (! $booking) {
            $this->scanStatus = 'error';
            $this->scanMessage = 'Data reservasi dengan kode "'.$code.'" tidak ditemukan.';

            return;
        }

        $this->resolvedBooking = $booking;
        $this->scanStatus = 'success';
        $this->scanMessage = 'E-Tiket ditemukan untuk '.$booking->room->name;
    }

    public function processRoomIdentifier(QrCodeService $qrService)
    {
        $this->resetScanState();

        $identifier = trim($this->roomIdentifierInput);
        if (empty($identifier)) {
            $this->scanStatus = 'error';
            $this->scanMessage = 'Silakan masukkan ID ruangan resmi.';

            return;
        }

        if (str_contains($identifier, '/qr/')) {
            $parts = explode('/qr/', $identifier);
            $identifier = end($parts);
        }

        $room = $qrService->resolveRoom($identifier);

        if (! $room) {
            $this->scanStatus = 'error';
            $this->scanMessage = 'ID Ruangan "'.$identifier.'" tidak terdaftar dalam sistem.';

            return;
        }

        $this->resolvedRoom = $room;

        return redirect()->route('rooms.show', $room->slug);
    }

    public function resetScanState()
    {
        $this->resolvedBooking = null;
        $this->resolvedRoom = null;
        $this->scanMessage = '';
        $this->scanStatus = '';
    }

    public function render()
    {
        return view('livewire.qr-scanner')
            ->layout('layouts.app', ['title' => 'Layanan E-Tiket & Cek Ruangan - SIMRU Teknik']);
    }
}
