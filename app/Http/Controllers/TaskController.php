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
    // Show task summary for logged-in employee
    public function index()
    {
        $employee = Auth::user()->employee;

        $totalTasks         = Task::where('employee_id', $employee->employee_id)->count();
        $toDoTasks          = Task::where('employee_id', $employee->employee_id)->where('status', 'to-do')->count();
        $inProgressTasks    = Task::where('employee_id', $employee->employee_id)->where('status', 'in-progress')->count();
        $inReviewTasks      = Task::where('employee_id', $employee->employee_id)->where('status', 'in-review')->count();
        $completedTasks     = Task::where('employee_id', $employee->employee_id)->where('status', 'completed')->count();

        // Get all requests to show in a table
        $query = Task::where('employee_id', $employee->employee_id)->orderBy('created_at', 'desc');
        $tasks = $query->get();
       
        return view('employee.employee-task', compact(
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
        return view('employee.createtask');
    }

    /**
     * Store a newly created resource in storage.
     */
    // Create new task
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'assigned_to'   => 'required|string|max:255',
            'assigned_by'   => 'required|string|max:255',
            'status'        => 'required|in:to-do,in-progress,in-review,completed',
            'notes'         => 'nullable|string',
            'due_date'      => 'nullable|date',
        ]);

        $task = new Task();
        $task->employee_id  = $employee->employee_id;
        $task->title        = $request->title;
        $task->description  = $request->description;
        $task->assigned_to  = $request->assigned_to;
        $task->assigned_by  = $employee->employee_id; // Assuming the assigner is the logged-in employee
        $task->status       = $request->status;
        $task->notes        = $request->notes;
        $task->due_date     = $request->due_date;

        $task->save();

        return redirect()->route('task.create')->with('success','Task created successfully!');
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
            'status' => 'required|in:to-do,in-progress,in-review,completed',
            'due_date' => 'nullable|date',
        ]);

        $task->status = $request->status;
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
