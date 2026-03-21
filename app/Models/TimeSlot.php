<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        'max_people',
        'price',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'max_people' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getAvailableSlotsForDate(string $date): int
    {
        $bookedSlots = $this->bookings()
            ->where('booking_date', $date)
            ->whereIn('booking_status', ['confirmed', 'checked_in'])
            ->sum('number_of_people');

        return max(0, $this->max_people - $bookedSlots);
    }

    public function isAvailableForDate(string $date, int $people): bool
    {
        return $this->getAvailableSlotsForDate($date) >= $people;
    }
}
