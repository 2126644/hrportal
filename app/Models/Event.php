<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'created_by',
        'event_name',
        'description',
        'event_date',
        'event_time',
        'event_location',
        'category',
        'capacity',
        'attendees',
        'price',
        'image',
        'event_status',
        'organizer',
        'tags',
        'rsvp_required'
    ];

    // Optional: format date for easy usage in Blade
    protected $casts = [
        'event_date' => 'date'
    ];

    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
