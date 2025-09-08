<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Show leave summary for logged-in employee
    public function index()
    {
        $employee = Auth::user()->employee;

        $leaveBalance   = 20 - Leave::where('employee_id', $employee->id)->where('status', 'approved')->sum('days'); // assuming 20 days yearly
        $pendingLeaves  = Leave::where('employee_id', $employee->id)->where('status', 'pending')->count();
        $approvedLeaves = Leave::where('employee_id', $employee->id)->where('status', 'approved')->count();

        return response()->json([
            'leave_balance'   => $leaveBalance,
            'pending_requests'=> $pendingLeaves,
            'approved_leaves' => $approvedLeaves,
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
    // Submit new leave request
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:255',
        ]);

        $days = (new \Carbon\Carbon($request->start_date))
                ->diffInDays(new \Carbon\Carbon($request->end_date)) + 1;

        Leave::create([
            'employee_id' => $employee->id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'days'        => $days,
            'reason'      => $request->reason,
            'status'      => 'pending',
        ]);

        return response()->json(['message' => 'Leave request submitted successfully']);
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
