<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Services\BookingService;
use App\Services\QrCodeService;
use Livewire\Component;

class BookingTicket extends Component
{
    public Booking $booking;

    public function mount(string $code)
    {
        $this->booking = Booking::with(['room', 'user', 'logs'])
            ->where('booking_code', $code)
            ->firstOrFail();
    }

    public function checkIn(BookingService $bookingService)
    {
        if ($bookingService->checkIn($this->booking)) {
            $this->booking->refresh();
            session()->flash('success', 'Check-In berhasil! Selamat menggunakan fasilitas ruangan.');
        } else {
            session()->flash('error', 'Check-in gagal. Pastikan status booking aktif dan sesuai jadwal.');
        }
    }

    public function checkOut(BookingService $bookingService)
    {
        if ($bookingService->checkOut($this->booking)) {
            $this->booking->refresh();
            session()->flash('success', 'Sesi penggunaan ruangan selesai. Terima kasih.');
        }
    }

    public function cancel(BookingService $bookingService)
    {
        if ($bookingService->cancelBooking($this->booking, 'Dibatalkan oleh pengguna melalui tiket digital')) {
            $this->booking->refresh();
            session()->flash('success', 'Reservasi berhasil dibatalkan.');
        }
    }

    public function render(QrCodeService $qrService)
    {
        $ticketPayload = $qrService->generateBookingPayload($this->booking);
        $qrSvg = $qrService->generateSvg($ticketPayload, 220);

        return view('livewire.booking-ticket', [
            'qrSvg' => $qrSvg,
            'ticketPayload' => $ticketPayload,
        ])->layout('layouts.app', ['title' => 'Tiket QR - '.$this->booking->booking_code]);
    }
}
