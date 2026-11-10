<?php

namespace App\Filament\Resources\Rooms\Widgets;

use App\Models\Room;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RoomStatsWidget extends BaseWidget
{
    public ?Room $record = null;

    protected function getStats(): array
    {
        if (! $this->record) {
            return [];
        }

        $now = Carbon::now();
        $totalBookings = $this->record->bookings()->count();
        $pendingBookings = $this->record->bookings()->where('status', 'pending')->count();
        $todayBookings = $this->record->bookings()->whereDate('start_time', Carbon::today())->count();
        $isCurrentlyInUse = $this->record->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->exists();

        $statusLabel = $this->record->status === 'maintenance'
            ? 'Sedang Pemeliharaan'
            : ($isCurrentlyInUse ? 'Terpakai Saat Ini' : 'Tersedia');

        $statusColor = $this->record->status === 'maintenance'
            ? 'danger'
            : ($isCurrentlyInUse ? 'warning' : 'success');

        return [
            Stat::make('Kondisi Real-Time Ruang', $statusLabel)
                ->description($this->record->code.' • '.$this->record->location)
                ->color($statusColor),

            Stat::make('Permohonan Pending', $pendingBookings)
                ->description('Menunggu persetujuan admin')
                ->color($pendingBookings > 0 ? 'warning' : 'success'),

            Stat::make('Jadwal Hari Ini', $todayBookings)
                ->description('Penggunaan ruangan hari ini')
                ->color('info'),

            Stat::make('Total Penggunaan', $totalBookings)
                ->description('Total sesi kuliah & peminjaman')
                ->color('primary'),
        ];
    }
}
