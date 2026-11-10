<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class RoomService
{
    public function getRooms(?string $search = null, ?string $status = null, ?int $minCapacity = null): Collection
    {
        return Room::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query, $s) => $query->where('status', $s))
            ->when($minCapacity, fn ($query, $cap) => $query->where('capacity', '>=', $cap))
            ->orderBy('name')
            ->get();
    }

    public function getRoomCurrentStatus(Room $room): array
    {
        if ($room->status === 'maintenance') {
            return [
                'state' => 'maintenance',
                'label' => 'Sedang Pemeliharaan',
                'badge_color' => 'red',
                'booking' => null,
            ];
        }

        $now = Carbon::now();

        $currentBooking = Booking::where('room_id', $room->id)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->first();

        if ($currentBooking) {
            return [
                'state' => 'occupied',
                'label' => 'Terpakai (Sedang Digunakan)',
                'badge_color' => 'red',
                'booking' => $currentBooking,
            ];
        }

        $upcomingBooking = Booking::where('room_id', $room->id)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('start_time', '>', $now)
            ->where('start_time', '<=', $now->copy()->addMinutes(60))
            ->orderBy('start_time')
            ->first();

        if ($upcomingBooking) {
            return [
                'state' => 'soon',
                'label' => 'Akan Digunakan Segera',
                'badge_color' => 'yellow',
                'booking' => $upcomingBooking,
            ];
        }

        return [
            'state' => 'available',
            'label' => 'Tersedia',
            'badge_color' => 'green',
            'booking' => null,
        ];
    }

    public function getTodaySchedule(Room $room): Collection
    {
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        return Booking::where('room_id', $room->id)
            ->whereIn('status', ['confirmed', 'checked_in', 'completed'])
            ->where(function ($q) use ($todayStart, $todayEnd) {
                $q->whereBetween('start_time', [$todayStart, $todayEnd])
                    ->orWhere(function ($inner) use ($todayStart) {
                        $inner->where('start_time', '<=', $todayStart)
                            ->where('end_time', '>=', $todayStart);
                    });
            })
            ->orderBy('start_time')
            ->get();
    }

    public function getUpcomingSchedule(Room $room, int $days = 7): Collection
    {
        $start = Carbon::now();
        $end = Carbon::now()->addDays($days)->endOfDay();

        return Booking::where('room_id', $room->id)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('end_time', '>=', $start)
            ->where('start_time', '<=', $end)
            ->orderBy('start_time')
            ->get();
    }
}
