<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Room;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Str;
use InvalidArgumentException;

class BookingService
{
    public function isSlotAvailable(int $roomId, Carbon|string $startTime, Carbon|string $endTime, ?int $excludeBookingId = null): bool
    {
        $room = Room::find($roomId);
        if (! $room || $room->status === 'maintenance') {
            return false;
        }

        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        if ($end->lessThanOrEqualTo($start)) {
            return false;
        }

        // Cek bentrok dengan Jadwal Kuliah Mingguan Rutin
        $dayOfWeekIso = $start->dayOfWeekIso;
        $startTimeStr = $start->format('H:i:s');
        $endTimeStr = $end->format('H:i:s');

        $hasScheduleConflict = Schedule::where('room_id', $roomId)
            ->where('status', 'active')
            ->where('day_of_week', $dayOfWeekIso)
            ->where(function ($q) use ($startTimeStr, $endTimeStr) {
                $q->where('start_time', '<', $endTimeStr)
                    ->where('end_time', '>', $startTimeStr);
            })
            ->exists();

        if ($hasScheduleConflict) {
            return false;
        }

        return ! Booking::where('room_id', $roomId)
            ->overlapping($start, $end, $excludeBookingId)
            ->exists();
    }

    public function createBooking(array $data): Booking
    {
        $roomId = (int) $data['room_id'];
        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_time']);

        if (! $this->isSlotAvailable($roomId, $startTime, $endTime)) {
            throw new InvalidArgumentException('Ruangan tidak tersedia pada rentang waktu yang dipilih.');
        }

        $booking = Booking::create([
            'booking_code' => $this->generateBookingCode(),
            'user_id' => $data['user_id'] ?? null,
            'room_id' => $roomId,
            'borrower_name' => $data['borrower_name'],
            'borrower_nim' => $data['borrower_nim'],
            'borrower_phone' => $data['borrower_phone'],
            'title' => $data['title'],
            'purpose' => $data['purpose'] ?? null,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $data['status'] ?? 'pending',
        ]);

        BookingLog::create([
            'booking_id' => $booking->id,
            'user_id' => $data['user_id'] ?? null,
            'action' => 'submitted_booking',
            'payload' => [
                'borrower_name' => $data['borrower_name'],
                'borrower_nim' => $data['borrower_nim'],
                'created_at' => now()->toIso8601String(),
                'ip' => request()->ip(),
            ],
        ]);

        return $booking;
    }

    public function approveBooking(Booking $booking, ?int $actorId = null): bool
    {
        if ($booking->status !== 'pending') {
            return false;
        }

        $booking->update([
            'status' => 'confirmed',
        ]);

        BookingLog::create([
            'booking_id' => $booking->id,
            'user_id' => $actorId,
            'action' => 'approved_by_admin',
            'payload' => [
                'timestamp' => now()->toIso8601String(),
            ],
        ]);

        return true;
    }

    public function rejectBooking(Booking $booking, string $reason, ?int $actorId = null): bool
    {
        if ($booking->status !== 'pending') {
            return false;
        }

        $booking->update([
            'status' => 'rejected',
            'cancellation_reason' => $reason,
        ]);

        BookingLog::create([
            'booking_id' => $booking->id,
            'user_id' => $actorId,
            'action' => 'rejected_by_admin',
            'payload' => [
                'reason' => $reason,
                'timestamp' => now()->toIso8601String(),
            ],
        ]);

        return true;
    }

    public function checkIn(Booking $booking, ?int $actorId = null): bool
    {
        if ($booking->status !== 'confirmed') {
            return false;
        }

        $booking->update([
            'status' => 'checked_in',
            'check_in_at' => now(),
        ]);

        BookingLog::create([
            'booking_id' => $booking->id,
            'user_id' => $actorId ?? $booking->user_id,
            'action' => 'checked_in',
            'payload' => [
                'timestamp' => now()->toIso8601String(),
            ],
        ]);

        return true;
    }

    public function checkOut(Booking $booking, ?int $actorId = null): bool
    {
        if ($booking->status !== 'checked_in') {
            return false;
        }

        $booking->update([
            'status' => 'completed',
            'check_out_at' => now(),
        ]);

        BookingLog::create([
            'booking_id' => $booking->id,
            'user_id' => $actorId ?? $booking->user_id,
            'action' => 'checked_out',
            'payload' => [
                'timestamp' => now()->toIso8601String(),
            ],
        ]);

        return true;
    }

    public function cancelBooking(Booking $booking, string $reason, ?int $actorId = null): bool
    {
        if (in_array($booking->status, ['completed', 'cancelled'], true)) {
            return false;
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);

        BookingLog::create([
            'booking_id' => $booking->id,
            'user_id' => $actorId ?? $booking->user_id,
            'action' => 'cancelled',
            'payload' => [
                'reason' => $reason,
                'timestamp' => now()->toIso8601String(),
            ],
        ]);

        return true;
    }

    public function generateBookingCode(): string
    {
        do {
            $code = 'BK-'.strtoupper(now()->format('Ymd')).'-'.strtoupper(Str::random(5));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }
}
