<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class BookingChartWidget extends ChartWidget
{
    protected ?string $heading = 'Tren Reservasi Laboratorium & Ruangan (7 Hari Terakhir)';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '180px';

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));

        $labels = $days->map(fn ($d) => $d->translatedFormat('d M'))->toArray();

        $data = $days->map(function ($d) {
            return Booking::whereDate('start_time', $d)->count();
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Reservasi',
                    'data' => $data,
                    'borderColor' => '#A1553E',
                    'backgroundColor' => 'rgba(161, 85, 62, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
