<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // <--- ENSURE THIS IS PRESENT
use App\Models\Category;

class Popup extends Model
{
    // ...
    protected $fillable = ['title', 'content', 'image', 'category_id', 'is_enabled'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}