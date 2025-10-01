<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    protected $primaryKey = 'employee_id';
    public $incrementing = false;   //manually assigned matric_no
    protected $keyType = 'string';

    protected $fillable = [
        'employee_id',
        'user_id',
        'full_name',
        'department',
        'position',
        'date_joined',
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
    ];

    protected $casts = [
        'date_joined' => 'date',
        'birthday' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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

