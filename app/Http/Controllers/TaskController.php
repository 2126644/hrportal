<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Department;
use App\Models\TaskAssignment;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        $projects  = Project::orderBy('project_name')->get();
        $employees = Employee::orderBy('full_name')->get();

        $query = Task::with([
            'project',
            'createdBy',
            'assignedTo.employee',
            'assignedTo.department',
        ])->orderBy('created_at', 'desc');

        // Employee: only tasks assigned to them (via pivot)
        if ($user->role_id !== 2 && $employee) {
            $query->whereHas('assignedTo', function ($q) use ($employee) {
                $q->where('task_assignments.employee_id', $employee->employee_id);
            });
        }

        if ($user->role_id === 2) {
            if ($request->filled('employee_id')) {
                $query->whereHas('assignedTo', function ($q) use ($request) {
                    $q->where('task_assignments.employee_id', $request->employee_id);
                });
            }

            if ($request->filled('department_id')) {
                $query->whereHas('assignedTo', function ($q) use ($request) {
                    $q->where('task_assignments.department_id', $request->department_id);
                });
            }
        }

        // Filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('task_name', 'like', "%{$request->search}%")
                    ->orWhere('id', $request->search);
            });
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('task_status')) {
            $query->where('task_status', $request->task_status);
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->get();

        // 📊 Stats
        $totalTasks      = $tasks->count();
        $toDoTasks       = $tasks->where('task_status', 'to-do')->count();
        $inProgressTasks = $tasks->where('task_status', 'in-progress')->count();
        $inReviewTasks   = $tasks->where('task_status', 'in-review')->count();
        $toReviewTasks   = $tasks->where('task_status', 'to-review')->count();
        $completedTasks  = $tasks->where('task_status', 'completed')->count();

        $view = $user->role_id == 2
            ? 'admin.admin-task'
            : 'employee.employee-task';

        return view($view, compact(
            'tasks',
            'projects',
            'employees',
            'totalTasks',
            'toDoTasks',
            'inProgressTasks',
            'inReviewTasks',
            'toReviewTasks',
            'completedTasks'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $employee = $user->employee;

        $role_id = $user->role_id;

        $employment   = $employee?->employment;
        $departmentId = $employment?->department_id;

        $projects = Project::orderBy('project_name')->get();

        // Employees from same department
        $employees = Employee::with('employment.department')
            ->whereHas('employment', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId)
                    ->whereHas('status', fn($qs) => $qs->where('name', 'active'));
            })
            ->get()
            ->map(fn($e) => [
                'id'         => $e->employee_id,
                'name'       => $e->full_name,
                'department' => $e->employment->department->name ?? 'N/A',
            ]);

        $departments = Department::orderBy('name')->get();

        $allEmployees = Employee::with('employment.department')->get()->map(fn($e) => [
            'id'         => $e->employee_id,
            'name'       => $e->full_name,
            'department' => $e->employment->department->name ?? 'N/A',
        ]);

        return view('employee.createtask', compact(
            'projects',
            'employees',
            'departments',
            'allEmployees',
            'role_id'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    // Create new task
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        $request->validate([
            'project_id'      => 'nullable|exists:projects,id',
            'task_name'       => 'required|string|max:255',
            'task_desc'       => 'nullable|string',
            'task_status'     => 'required|in:to-do,in-progress,in-review,to-review,completed',
            'due_date'        => 'nullable|date',
            'notes'           => 'nullable|string',
            'department_ids'  => 'array',
            'department_ids.*' => 'exists:departments,id',
            'employee_ids'    => 'array',
            'employee_ids.*'  => 'exists:employees,employee_id',
        ]);

        $task = Task::create([
            'project_id' => $request->project_id,
            'task_name'  => $request->task_name,
            'task_desc'  => $request->task_desc,
            'task_status' => $request->task_status,
            'due_date'   => $request->due_date,
            'notes'      => $request->notes,
            'created_by' => Auth::id(),
        ]);

        // Assign departments
        foreach ($request->department_ids ?? [] as $deptId) {
            $task->assignedTo()->create([
                'department_id' => $deptId,
            ]);
        }

        // Assign employees
        $employeeIds = Employee::whereIn('employee_id', $request->employee_ids ?? [])
            ->pluck('employee_id');

        foreach ($employeeIds as $empId) {
            TaskAssignment::firstOrCreate([
                'task_id'    => $task->id,
                'employee_id' => $empId,
            ]);
        }

        return redirect()
            ->route('task.index.employee')
            ->with('success', 'Task created successfully');
    }

    // Mark task as completed
    public function complete($id)
    {
        $task = Task::findOrFail($id);
        $task->update(['status' => 'completed']);

        return response()->json(['message' => 'Task marked as completed']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        $employee = $user->employee;

        // simple authorization: admin or owner
        if ($user->role_id !== 2 && $task->created_by !== ($employee->employee_id ?? null)) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'task_name' => 'required|string|max:255',
            'task_desc' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'task_status' => 'required|in:to-do,in-progress,in-review,to-review,completed',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $task->update([
            'task_name' => $request->task_name,
            'task_desc' => $request->task_desc,
            'project_id' => $request->project_id,
            'task_status' => $request->task_status,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
