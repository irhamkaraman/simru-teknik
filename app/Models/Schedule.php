<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'room_id',
        'day_of_week',
        'start_time',
        'end_time',
        'title',
        'lecturer_name',
        'lecturer_code',
        'phone',
        'status',
        'purpose',
    ];

    public const DAYS = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? 'Tidak Diketahui';
    }

    public function getFormattedTimeAttribute(): string
    {
        $start = substr((string) $this->start_time, 0, 5);
        $end = substr((string) $this->end_time, 0, 5);

        return "{$start} - {$end} WIB";
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeForDay(Builder $query, int $dayOfWeek): Builder
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    public function isOngoingNow(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = Carbon::now(config('app.timezone', 'Asia/Jakarta'));
        if ($now->dayOfWeekIso !== (int) $this->day_of_week) {
            return false;
        }

        $currentTime = $now->format('H:i:s');
        $startTime = Carbon::parse($this->start_time)->format('H:i:s');
        $endTime = Carbon::parse($this->end_time)->format('H:i:s');

        return $currentTime >= $startTime && $currentTime < $endTime;
    }
}
