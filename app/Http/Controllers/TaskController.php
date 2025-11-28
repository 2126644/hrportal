<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Employee;
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

        // Preload supporting lists for the filters (blade)
        $projects = Project::orderBy('project_name')->get();
        $employees = Employee::orderBy('full_name')->get();

        $query = Task::orderBy('created_at', 'desc');

        // --- Scope to employee when not admin ---
        if ($user->role_id !== 2) {
            if ($employee) {
                $query->where('assigned_to', $employee->employee_id)
                    ->orWhere('created_by', $employee->employee_id);
            }
        }

        // --- Admin filters ---
        if ($user->role_id === 2) {
            // status filter: server-side
            if ($request->filled('status')) {
                $query->where('task_status', $request->status);
            }
            // Search — Task name OR ID
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('task_name', 'like', '%' . $search . '%')
                        ->orWhere('id', $search);
                });
            }

            // project filter
            if ($request->filled('project_id')) {
                $query->where('project_id', $request->project_id);
            }

            // created_by filter (accepts either numeric id or employee_id string)
            if ($request->filled('created_by')) {
                $createdByInput = $request->created_by;
                $createdByEmployee = Employee::where('id', $createdByInput)
                    ->orWhere('employee_id', $createdByInput)
                    ->first();
                if ($createdByEmployee) {
                    $query->where('created_by', $createdByEmployee->employee_id);
                } else {
                    // if a raw employee_id string was passed
                    $query->where('created_by', $createdByInput);
                }
            }

            // assigned_to filter
            if ($request->filled('assigned_to')) {
                $assignedToInput = $request->assigned_to;
                $assignedToEmployee = Employee::where('id', $assignedToInput)
                    ->orWhere('employee_id', $assignedToInput)
                    ->first();
                if ($assignedToEmployee) {
                    $query->where('assigned_to', $assignedToEmployee->employee_id);
                } else {
                    $query->where('assigned_to', $assignedToInput);
                }
            }

            // assigned_by filter
            if ($request->filled('assigned_by')) {
                $assignedByInput = $request->assigned_by;
                $assignedByEmployee = Employee::where('id', $assignedByInput)
                    ->orWhere('employee_id', $assignedByInput)
                    ->first();
                if ($assignedByEmployee) {
                    $query->where('assigned_by', $assignedByEmployee->employee_id);
                } else {
                    $query->where('assigned_by', $assignedByInput);
                }
            }
        }

        // --- Common filters ---
        if ($request->filled('task_name')) {
            $query->where('task_name', 'like', '%' . $request->task_name . '%');
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->get();

        $totalTasks = $tasks->count();
        $toDoTasks = $tasks->where('task_status', 'to-do')->count();
        $inProgressTasks = $tasks->where('task_status', 'in-progress')->count();
        $inReviewTasks = $tasks->where('task_status', 'in-review')->count();
        $toReviewTasks = $tasks->where('task_status', 'to-review')->count();
        $completedTasks = $tasks->where('task_status', 'completed')->count();

        $view = $user->role_id == 2 ? 'admin.admin-task' : 'employee.employee-task';

        return view($view, compact(
            'totalTasks',
            'toDoTasks',
            'inProgressTasks',
            'inReviewTasks',
            'toReviewTasks',
            'completedTasks',
            'tasks',
            'projects',
            'employees'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role_id = Auth::user()->role_id;
        return view('employee.createtask', compact('role_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // Create new task
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        $request->validate([
            'task_name'     => 'required|string|max:255',
            'task_desc'     => 'nullable|string',
            'assigned_to'   => 'required|string|max:255',
            'assigned_by'   => 'required|string|max:255',
            'task_status'   => 'required|in:to-do,in-progress,in-review,completed',
            'notes'         => 'nullable|string',
            'due_date'      => 'nullable|date',
        ]);

        $task = new Task();
        $task->created_by   = $employee->employee_id;
        $task->task_name    = $request->task_name;
        $task->task_desc    = $request->task_desc;
        $task->assigned_to  = $request->assigned_to;
        $task->assigned_by  = $employee->employee_id; // Assuming the assigner is the logged-in employee
        $task->task_status  = $request->task_status;
        $task->notes        = $request->notes;
        $task->due_date     = $request->due_date;

        $task->save();

        return redirect()->route('task.create')->with('success', 'Task created successfully!');
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
        $request->validate([
            'task_status' => 'required|in:to-do,in-progress,in-review,completed',
            'due_date' => 'nullable|date',
        ]);

        $task->task_status = $request->task_status;
        $task->due_date = $request->due_date;
        $task->save();

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
