<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reference',
        'time_slot_id',
        'package_id',
        'booking_date',
        'customer_name',
        'customer_email',
        'customer_phone',
        'number_of_people',
        'total_price',
        'payment_status',
        'booking_status',
        'qr_code',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'number_of_people' => 'integer',
        'total_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = 'RAFT-' . strtoupper(Str::random(8));
            }
        });
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('booking_status', 'confirmed');
    }

    public function scopePendingPayment($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function markAsPaid(): void
    {
        $this->update(['payment_status' => 'paid']);
    }

    public function markAsPaidAtSite(): void
    {
        $this->update(['payment_status' => 'at_site']);
    }

    public function checkIn(): void
    {
        $this->update(['booking_status' => 'checked_in']);
    }

    public function complete(): void
    {
        $this->update(['booking_status' => 'completed']);
    }

    public function cancel(): void
    {
        $this->update(['booking_status' => 'cancelled']);
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->timeSlot
            ? $this->timeSlot->start_time->format('H:i') . ' - ' . $this->timeSlot->end_time->format('H:i')
            : '';
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'Pending Payment',
            'paid' => 'Paid Online',
            'at_site' => 'Pay at Site',
            default => 'Unknown',
        };
    }

    public function getBookingStatusLabelAttribute(): string
    {
        return match($this->booking_status) {
            'confirmed' => 'Confirmed',
            'checked_in' => 'Checked In',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => 'Unknown',
        };
    }
}
