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

        $totalTasks         = Task::where('employee_id', $employee->id)->count();
        $toDoTasks          = Task::where('employee_id', $employee->id)->where('status', 'to_do')->count();
        $inProgressTasks    = Task::where('employee_id', $employee->id)->where('status', 'in_progress')->count();
        $inReviewTasks      = Task::where('employee_id', $employee->id)->where('status', 'in_review')->count();
        $completedTasks     = Task::where('employee_id', $employee->id)->where('status', 'completed')->count();
        // $overdueTasks   = Task::where('employee_id', $employee->id)
        //                     ->where('status', '!=', 'completed')
        //                     ->where('due_date', '<', now())
        //                     ->count();

        return view('employee.task', compact(
            'totalTasks',
            'toDoTasks',
            'inProgressTasks',
            'inReviewTasks',
            'completedTasks'
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
            'status'        => 'required|in:pending,completed',
            'notes'         => 'nullable|string',
            'due_date'      => 'nullable|date',
        ]);

        // Task::create([
        //     'employee_id' => $employee->employee_id,
        //     'title'       => $request->title,
        //     'description' => $request->description,
        //     'assigned_to'  => $request->assigned_to,
        //     'assigned_by'  => $request->assigned_by,
        //     'status'      => $request->status,
        //     'notes'       => $request->notes,
        //     'due_date'    => $request->due_date,
        // ]);

        $task = new Task();
        $task->employee_id  = $employee->employee_id;
        $task->title        = $request->title;
        $task->description  = $request->description;
        $task->assigned_to  = $request->assigned_to;
        $task->assigned_by  = $request->assigned_by;
        $task->status       = $request->status;
        $task->notes        = $request->notes;
        $task->due_date     = $request->due_date;

        $task->status = 'pending';
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
