<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeService
{
    public function generateSvg(string $data, int $size = 200): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd
        );

        $writer = new Writer($renderer);

        return $writer->writeString($data);
    }

    public function generateDataUri(string $data, int $size = 200): string
    {
        $svg = $this->generateSvg($data, $size);

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    public function generateRoomPayload(Room $room): string
    {
        return route('rooms.qr', ['identifier' => $room->qr_identifier]);
    }

    public function generateBookingPayload(Booking $booking): string
    {
        return route('bookings.ticket', ['code' => $booking->booking_code]);
    }

    public function resolveRoom(string $identifier): ?Room
    {
        return Room::where('qr_identifier', $identifier)->first();
    }

    public function resolveBooking(string $code): ?Booking
    {
        return Booking::with(['room', 'user'])
            ->where('booking_code', $code)
            ->first();
    }
}
