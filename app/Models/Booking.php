<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'booking_code',
    'user_id',
    'room_id',
    'borrower_name',
    'borrower_nim',
    'borrower_phone',
    'title',
    'purpose',
    'start_time',
    'end_time',
    'status',
    'check_in_at',
    'check_out_at',
    'cancellation_reason',
])]
class Booking extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'check_in_at' => 'datetime',
            'check_out_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BookingLog::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'checked_in']);
    }

    public function scopeOverlapping(Builder $query, Carbon|string $startTime, Carbon|string $endTime, ?int $excludeBookingId = null): Builder
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->where(function (Builder $q) use ($startTime, $endTime) {
                $q->where(function (Builder $inner) use ($startTime, $endTime) {
                    $inner->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->when($excludeBookingId, fn (Builder $q) => $q->where('id', '!=', $excludeBookingId));
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isCheckedIn(): bool
    {
        return $this->status === 'checked_in';
    }
}
