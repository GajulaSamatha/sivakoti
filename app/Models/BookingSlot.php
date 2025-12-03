<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_pooja_id',
        'date',
        'start_time',
        'end_time',
        'max_bookings',
        'current_bookings',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    // One slot belongs to one Puja
    public function eventPooja()
    {
        return $this->belongsTo(EventPooja::class);
    }

    // One slot can have many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}