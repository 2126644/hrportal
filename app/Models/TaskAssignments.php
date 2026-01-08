<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignments extends Model
{
    /** @use HasFactory<\Database\Factories\TaskAssignmentsFactory> */
    use HasFactory;

    protected $fillable = ['task_id', 'department_id', 'employee_id'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
