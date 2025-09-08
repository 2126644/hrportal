<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Show task summary for logged-in employee
    public function index()
    {
        $employee = Auth::user()->employee;

        $pendingTasks   = Task::where('employee_id', $employee->id)->where('status', 'pending')->count();
        $completedTasks = Task::where('employee_id', $employee->id)->where('status', 'completed')->count();
        $overdueTasks   = Task::where('employee_id', $employee->id)
                            ->where('status', '!=', 'completed')
                            ->where('due_date', '<', now())
                            ->count();

        return response()->json([
            'pending'   => $pendingTasks,
            'completed' => $completedTasks,
            'overdue'   => $overdueTasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // Create new task
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'required|date',
        ]);

        Task::create([
            'employee_id' => $employee->id,
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'status'      => 'pending',
        ]);

        return response()->json(['message' => 'Task created successfully']);
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
