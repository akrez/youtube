<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Videos extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
        'formats' => 'json',
        'adaptive_formats' => 'json',
    ];

    public function scopeFilterRecently(Builder $query): void
    {
        $query->where('synced_at', '>=', now()->subHours(1)->toDateTimeString());
    }
}
