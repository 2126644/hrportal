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

        // query model with relationships
        $query = Task::with(['project', 'assignedTo', 'createdBy'])->orderBy('created_at', 'desc');

        // Employee — only see their own tasks
        if ($user->role_id !== 2) { // employee roles
            $query->where('assigned_to', $employee->employee_id);
        }

        // Filter by task name or ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('task_name', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter by created by
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by task status
        if ($request->filled('task_status')) {
            $query->where('task_status', $request->task_status);
        }

        // Filter by due date
        if ($request->filled('due_date')) {
            $query->where('due_date', $request->due_date);
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
        $employees = Employee::orderBy('full_name')->get();
        $projects = Project::orderBy('project_name')->get();

        return view('employee.createtask', compact('role_id', 'employees', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // Create new task
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;
        if (! $employee) {
            return redirect()->back()->with('error', 'No employee profile found for this user.');
        }

        $request->validate([
            'project_id'    => 'nullable|exists:projects,id',
            'task_name'     => 'required|string|max:255',
            'task_desc'     => 'nullable|string',
            // ensure assigned_to maps to an existing employee.employee_id
            'assigned_to'   => 'required|string|exists:employees,employee_id',
            'task_status'   => 'required|in:to-do,in-progress,in-review,to-review,completed',
            'notes'         => 'nullable|string',
            'due_date'      => 'nullable|date',
        ]);

        $task = new Task();
        $task->created_by   = $employee->employee_id;
        $task->project_id   = $request->project_id;
        $task->task_name    = $request->task_name;
        $task->task_desc    = $request->task_desc;
        $task->assigned_to  = $request->assigned_to;
        $task->task_status  = $request->task_status;
        $task->notes        = $request->notes;
        $task->due_date     = $request->due_date;

        $task->save();

        return redirect()->route('task.index.employee')->with('success', 'Task created successfully!');
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
