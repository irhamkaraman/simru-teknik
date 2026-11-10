<?php

namespace App\Models;

use Illuminate\Support\Str;

class Schedule extends Booking
{
    protected $table = 'bookings';

    protected static function booted(): void
    {
        static::creating(function ($schedule) {
            if (empty($schedule->booking_code)) {
                $schedule->booking_code = 'JDW-'.strtoupper(now()->format('Ymd')).'-'.strtoupper(Str::random(4));
            }
            if (empty($schedule->status)) {
                $schedule->status = 'confirmed';
            }
        });
    }
}
