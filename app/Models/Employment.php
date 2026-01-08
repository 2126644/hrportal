<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employment extends Model
{
    /** @use HasFactory<\Database\Factories\EmploymentFactory> */
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'department_id',
        'employment_type',
        'employment_status',
        'company_branch', // enum
        'report_to', // enum employee_id
        'position',
        'date_joined',
        'probation_start',
        'probation_end',
        'suspended_start',
        'suspended_end',
        'resigned_date',
        'termination_date',
        'work_start_time',
        'work_end_time',
    ];

    protected $casts = [
        'date_joined' => 'date',
        'probation_start' => 'date',
        'probation_end' => 'date',
        'suspended_start' => 'date',
        'suspended_end' => 'date',
        'resigned_date' => 'date',
        'termination_date' => 'date',
        'work_start_time' => 'datetime:H:i',
        'work_end_time' => 'datetime:H:i'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function reportToEmployee()
    {
        return $this->belongsTo(Employee::class, 'report_to', 'employee_id');
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
