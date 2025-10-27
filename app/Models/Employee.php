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
        'email',
        'phone_number',
        'address',
        'ic_number',
        'marital_status',
        'gender',
        'birthday',
        'nationality',
        'emergency_contact'
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'employee_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'employee_id', 'employee_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to', 'employee_id');
    }

    public function employment()
    {
        return $this->hasOne(Employment::class, 'employee_id', 'employee_id');
    }
}
