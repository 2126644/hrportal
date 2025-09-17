<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'event_name',
        'event_date',
        'event_time',
        'event_location',
    ];

    // Optional: format date for easy usage in Blade
    protected $casts = [
        'event_date' => 'date',
    ];
}
