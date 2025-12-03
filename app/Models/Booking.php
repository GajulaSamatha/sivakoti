<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'devotee_id',
        'event_pooja_id',
        'booking_slot_id',
        'status',
        'amount',
        'booking_reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships — exactly what you asked
    public function devotee()
    {
        return $this->belongsTo(Devotee::class);
    }

    public function eventPooja()
    {
        return $this->belongsTo(EventPooja::class);
    }

    public function bookingSlot()
    {
        return $this->belongsTo(BookingSlot::class);
    }
}