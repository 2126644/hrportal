<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'full_name',
        'department',
        'position',
        'email',
        'phone_number',
        'address',
        'ic_number',
        'marital_status',
        'gender',
        'birthday',
        'nationality',
        'emergency_contact',
        'profile_pic',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
}
