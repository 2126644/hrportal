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
        'assigned_to',
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
        return $this->belongsTo(Employee::class, 'assigned_to', 'employee_id');
    }
}
