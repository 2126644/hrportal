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
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_relationship',
        'highest_education_level',
        'highest_education_institution',
        'graduation_year'
    ];

    protected $casts = [
        'birthday' => 'date',
        'graduation_year' => 'integer',
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

    public function employment()
    {
        return $this->hasOne(Employment::class, 'employee_id', 'employee_id');
    }

    public function approvers()
    {
        return $this->belongsToMany(
            Employee::class,
            'employment_approvers',
            'employee_id',
            'approver_id'
        )->withPivot('level')->orderBy('pivot_level');
    }

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class, 'employee_id', 'employee_id');
    }

    public function tasks()
    {
        return $this->hasManyThrough(
            Task::class,
            TaskAssignment::class,
            'employee_id', // FK on task_assignments
            'id',          // FK on tasks
            'employee_id', // local key on employees
            'task_id'      // local key on task_assignments
        );
    }
}
