<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $todayBookings = Booking::whereDate('start_time', Carbon::today())->count();
        $completedBookings = Booking::where('status', 'completed')->count();

        return [
            Stat::make('Permohonan Pending', $pendingBookings)
                ->description('Menunggu persetujuan admin')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingBookings > 0 ? 'warning' : 'success')
                ->chart([3, 5, 2, 6, 4, $pendingBookings]),

            Stat::make('Kesiapan Lab & Ruang', "{$availableRooms} / {$totalRooms}")
                ->description('Fasilitas aktif siap pakai')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([$totalRooms, $totalRooms, $availableRooms, $availableRooms]),

            Stat::make('Reservasi Hari Ini', $todayBookings)
                ->description('Agenda terjadwal hari ini')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info')
                ->chart([1, 3, 2, 4, 3, $todayBookings]),

            Stat::make('Selesai Terlaksana', $completedBookings)
                ->description('Total sesi penggunaan selesai')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary')
                ->chart([2, 4, 6, 8, $completedBookings]),
        ];
    }
}
