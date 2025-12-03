<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPooja extends Model
{
    use HasFactory;
    
    // 1. FILLABLE: Mass Assignment Protection
    // List all fields that can be safely set via Category::create() or $category->update()
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'location',
        'price',
        'image',
        'status',
        'is_enabled',
    ];

    // 2. CASTS: Automatic Type Conversion
    // Converts database values to useful PHP types automatically
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_enabled' => 'boolean',
        'price' => 'float',
    ];

    // 3. RELATIONSHIP: Link to the Category Model
    // Defines the inverse of the One-to-Many relationship
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function bookingSlots()
    {
        return $this->hasMany(BookingSlot::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}