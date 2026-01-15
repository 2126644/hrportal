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
        'date_of_employment',
        'probation_start',
        'probation_end',
        'suspension_start',
        'suspension_end',
        'resignation_date',
        'last_working_day',
        'termination_date',
        'work_start_time',
        'work_end_time',
    ];

    protected $casts = [
        'date_of_employment' => 'date',
        'probation_start' => 'date',
        'probation_end' => 'date',
        'suspension_start' => 'date',
        'suspension_end' => 'date',
        'resignation_date' => 'date',
        'last_working_day' => 'date',
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
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
