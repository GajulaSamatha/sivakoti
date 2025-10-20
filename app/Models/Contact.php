<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    
    // Define the columns that can be filled from the form submission
    protected $fillable = [
    'name',
    'email',
    'phone',
    'message',
    'read_at', // <-- ADD THIS
];
}
