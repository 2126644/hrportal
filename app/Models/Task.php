<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'created_by',
        'task_name',
        'task_desc',
        'task_status',
        'notes',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'created_by', 'employee_id');
    }

    public function assignedTo()
    {
        return $this->hasMany(TaskAssignments::class);
    }

    // App\Models\Task.php

    public function assignmentSummary(): array
    {
        $employees = $this->assignedTo
            ->pluck('employee')
            ->filter();

        $departments = $this->assignedTo
            ->pluck('department')
            ->filter()
            ->unique('id');

        return [
            'employees'   => $employees->pluck('full_name')->values(),
            'departments' => $departments->pluck('department_name')->values(),
        ];
    }
}
