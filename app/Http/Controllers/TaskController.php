<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        $query = Task::orderBy('created_at', 'desc');

        // Only apply filters if the inputs exist
        if ($user->role_id === 2) {
            if ($request->filled('employee')) {
                $query->where('assigned_to', 'like', '%' . $request->employee . '%')
                    ->orWhere('assigned_by', 'like', '%' . $request->employee . '%');
            }
        } else {
            $query->where('assigned_to', $employee->employee_id);
        }

        if ($request->filled('task_name')) {
            $query->where('task_name', 'like', '%' . $request->task_name . '%');
        }

        if ($request->filled('due_date')) {
            $query->where('due_date', $request->due_date);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', 'like', '%' . $request->assigned_to . '%');
        }

        if ($request->filled('assigned_by')) {
            $query->where('assigned_by', 'like', '%' . $request->assigned_by . '%');
        }

        // Finally fetch results
        $tasks = $query->get();

        $totalTasks         = $tasks->count();
        $toDoTasks          = $tasks->where('task_status', 'to-do')->count();
        $inProgressTasks    = $tasks->where('task_status', 'in-progress')->count();
        $inReviewTasks      = $tasks->where('task_status', 'in-review')->count();
        $toReviewTasks      = $tasks->where('task_status', 'to-review')->count();
        $completedTasks     = $tasks->where('task_status', 'completed')->count();

        $view = $user->role_id == 2 ? 'admin.admin-task' : 'employee.employee-task';

        return view($view, compact(
            'totalTasks',
            'toDoTasks',
            'inProgressTasks',
            'inReviewTasks',
            'completedTasks',
            'tasks'
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
