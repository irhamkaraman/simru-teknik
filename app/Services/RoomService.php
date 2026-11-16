<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Schedule;
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
                'schedule' => null,
                'activity_name' => null,
                'pic_name' => null,
                'time_range' => null,
            ];
        }

        $now = $this->getNow();
        $currentDayIso = $now->dayOfWeekIso; // 1 (Senin) .. 7 (Minggu)
        $currentTime = $now->format('H:i:s');

        // 1. Cek apakah ada JADWAL KULIAH RUTIN yang sedang berlangsung saat ini
        $currentSchedule = Schedule::where('room_id', $room->id)
            ->where('status', 'active')
            ->where('day_of_week', $currentDayIso)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>', $currentTime)
            ->first();

        if ($currentSchedule) {
            return [
                'state' => 'occupied',
                'label' => 'Terpakai (Sedang Digunakan)',
                'badge_color' => 'red',
                'booking' => null,
                'schedule' => $currentSchedule,
                'activity_name' => $currentSchedule->title,
                'pic_name' => $currentSchedule->lecturer_name,
                'time_range' => $currentSchedule->formatted_time,
            ];
        }

        // 2. Cek apakah ada BOOKING RUANGAN yang sedang berlangsung saat ini
        $currentBooking = Booking::where('room_id', $room->id)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->first();

        if ($currentBooking) {
            $startTime = Carbon::parse($currentBooking->start_time)->format('H:i');
            $endTime = Carbon::parse($currentBooking->end_time)->format('H:i');

            return [
                'state' => 'occupied',
                'label' => 'Terpakai (Sedang Digunakan)',
                'badge_color' => 'red',
                'booking' => $currentBooking,
                'schedule' => null,
                'activity_name' => $currentBooking->title,
                'pic_name' => $currentBooking->borrower_name,
                'time_range' => "{$startTime} - {$endTime} WIB",
            ];
        }

        // 3. Cek apakah ada JADWAL KULIAH yang akan mulai dalam 60 menit ke depan
        $soonTime = $now->copy()->addMinutes(60)->format('H:i:s');
        $upcomingSchedule = Schedule::where('room_id', $room->id)
            ->where('status', 'active')
            ->where('day_of_week', $currentDayIso)
            ->where('start_time', '>', $currentTime)
            ->where('start_time', '<=', $soonTime)
            ->orderBy('start_time')
            ->first();

        if ($upcomingSchedule) {
            return [
                'state' => 'soon',
                'label' => 'Akan Digunakan Segera',
                'badge_color' => 'yellow',
                'booking' => null,
                'schedule' => $upcomingSchedule,
                'activity_name' => $upcomingSchedule->title,
                'pic_name' => $upcomingSchedule->lecturer_name,
                'time_range' => $upcomingSchedule->formatted_time,
            ];
        }

        // 4. Cek apakah ada BOOKING yang akan mulai dalam 60 menit ke depan
        $upcomingBooking = Booking::where('room_id', $room->id)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('start_time', '>', $now)
            ->where('start_time', '<=', $now->copy()->addMinutes(60))
            ->orderBy('start_time')
            ->first();

        if ($upcomingBooking) {
            $startTime = Carbon::parse($upcomingBooking->start_time)->format('H:i');
            $endTime = Carbon::parse($upcomingBooking->end_time)->format('H:i');

            return [
                'state' => 'soon',
                'label' => 'Akan Digunakan Segera',
                'badge_color' => 'yellow',
                'booking' => $upcomingBooking,
                'schedule' => null,
                'activity_name' => $upcomingBooking->title,
                'pic_name' => $upcomingBooking->borrower_name,
                'time_range' => "{$startTime} - {$endTime} WIB",
            ];
        }

        return [
            'state' => 'available',
            'label' => 'Tersedia',
            'badge_color' => 'green',
            'booking' => null,
            'schedule' => null,
            'activity_name' => null,
            'pic_name' => null,
            'time_range' => null,
        ];
    }

    public function getTodaySchedule(Room $room): array
    {
        $now = $this->getNow();
        $currentDayIso = $now->dayOfWeekIso;
        $items = [];

        // 1. Jadwal Kuliah Hari Ini
        $schedules = Schedule::where('room_id', $room->id)
            ->where('status', 'active')
            ->where('day_of_week', $currentDayIso)
            ->get();

        foreach ($schedules as $schedule) {
            $startStr = substr((string) $schedule->start_time, 0, 5);
            $endStr = substr((string) $schedule->end_time, 0, 5);
            $isOngoing = $schedule->isOngoingNow();

            $items[] = [
                'type' => 'schedule',
                'title' => $schedule->title,
                'pic' => $schedule->lecturer_name.($schedule->lecturer_code ? ' ('.$schedule->lecturer_code.')' : ''),
                'time_start' => $startStr,
                'time_end' => $endStr,
                'time_range' => "{$startStr} - {$endStr} WIB",
                'is_ongoing' => $isOngoing,
                'badge_label' => $isOngoing ? 'Sedang Digunakan (Kuliah)' : 'Jadwal Kuliah',
                'badge_color' => $isOngoing ? 'red' : 'blue',
                'notes' => $schedule->purpose,
                'sort_key' => $startStr,
            ];
        }

        // 2. Booking Khusus Hari Ini
        $bookings = Booking::where('room_id', $room->id)
            ->whereIn('status', ['confirmed', 'checked_in', 'completed'])
            ->whereDate('start_time', Carbon::today())
            ->with('user')
            ->get();

        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->start_time);
            $end = Carbon::parse($booking->end_time);
            $startStr = $start->format('H:i');
            $endStr = $end->format('H:i');
            $isOngoing = $now->between($booking->start_time, $booking->end_time);

            $items[] = [
                'type' => 'booking',
                'title' => $booking->title,
                'pic' => $booking->borrower_name.($booking->user ? ' ('.($booking->user->department ?? 'Mahasiswa').')' : ''),
                'time_start' => $startStr,
                'time_end' => $endStr,
                'time_range' => "{$startStr} - {$endStr} WIB",
                'is_ongoing' => $isOngoing,
                'badge_label' => $isOngoing ? 'Sedang Digunakan (Reservasi)' : ($booking->status === 'checked_in' ? 'Aktif (Checked In)' : 'Reservasi Khusus'),
                'badge_color' => $isOngoing ? 'red' : 'emerald',
                'notes' => $booking->purpose,
                'sort_key' => $startStr,
            ];
        }

        usort($items, fn ($a, $b) => strcmp($a['sort_key'], $b['sort_key']));

        return $items;
    }

    public function getWeeklySchedules(Room $room): array
    {
        $schedules = Schedule::where('room_id', $room->id)
            ->where('status', 'active')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $grouped = [];
        foreach (Schedule::DAYS as $dayIndex => $dayName) {
            $grouped[$dayIndex] = [
                'day_name' => $dayName,
                'schedules' => $schedules->where('day_of_week', $dayIndex)->values(),
            ];
        }

        return $grouped;
    }

    public function getUpcomingSchedule(Room $room, int $days = 7): Collection
    {
        $start = $this->getNow();
        $end = $this->getNow()->addDays($days)->endOfDay();

        return Booking::where('room_id', $room->id)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('end_time', '>=', $start)
            ->where('start_time', '<=', $end)
            ->orderBy('start_time')
            ->get();
    }

    public function getNow(): Carbon
    {
        return Carbon::now(config('app.timezone', 'Asia/Jakarta'));
    }
}
