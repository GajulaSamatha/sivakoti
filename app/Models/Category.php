<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Category extends Model implements Sortable
{
    use SortableTrait;

    protected $fillable = [
        'title', 'slug', 'description', 'start_date', 'end_date',
        'parent_id', 'order_column', 'is_active', 'status'
    ];

    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order_column');
    }

    // Auto disable when end_date passed
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(function ($query) {
            $query->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now()->toDateString());
            })->orWhere('is_active', true);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('status', 'published')
                     ->where(function ($q) {
                         $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                     });
    }
}