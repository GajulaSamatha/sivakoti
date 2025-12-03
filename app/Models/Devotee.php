<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Devotee extends Model implements AuthenticatableContract
{
    use HasFactory, Notifiable, Authenticatable;

    protected $fillable = [
        'username',
        'phone_number',
        'alternate_phone_number',
        'gotram',
        'family_details',
        'date_of_birth',
        'anniversary',
        'email',
        'address',
        'password',
        'gotram',
        'date_of_birth',
        'anniversary',
        'family_details',
        'alternative_phone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}