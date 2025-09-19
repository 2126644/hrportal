<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Show leave summary for logged-in employee
    public function index()
    {
        $employee = Auth::user()->employee;

        // Total approved days used
        $usedDays = Leave::where('employee_id', $employee->employee_id)->where('status', 'approved')->sum('days');

        $leaveBalance   = 14 - $usedDays; // assuming 14 days AL
        $pendingLeaves  = Leave::where('employee_id', $employee->employee_id)->where('status', 'pending')->count();
        $approvedLeaves = Leave::where('employee_id', $employee->employee_id)->where('status', 'approved')->count();
        $totalRequests  = Leave::where('employee_id', $employee->employee_id)->count();

        // Get all requests to show in a table
        $query = Leave::where('employee_id', $employee->employee_id)->orderBy('created_at', 'desc');
        $leaves = $query->get();

        return view('employee.leave', compact(
            'totalRequests',
            'approvedLeaves',
            'pendingLeaves',
            'leaveBalance',
            'usedDays',
            'leaves'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.applyleave');
    }

    /**
     * Store a newly created resource in storage.
     */
    // Submit new leave request
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        $request->validate([
            'leave_type'    => 'required|string|max:50',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string|max:255',
            'attachment'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $days = (new \Carbon\Carbon($request->start_date))
            ->diffInDays(new \Carbon\Carbon($request->end_date)) + 1;

        // Leave::create([
        //     'employee_id' => $employee->id,
        //     'start_date'  => $request->start_date,
        //     'end_date'    => $request->end_date,
        //     'days'        => $days,
        //     'reason'      => $request->reason,
        //     'status'      => 'pending',
        // ]);

        $leave = new Leave();
        $leave->employee_id  = $employee->employee_id;
        $leave->name         = $employee->full_name; // assuming Employee has 'name'
        $leave->applied_date = Carbon::now()->toDateString();
        $leave->leave_type   = $request->leave_type;
        $leave->reason       = $request->reason;
        $leave->start_date   = $request->start_date;
        $leave->end_date     = $request->end_date;
        $leave->days         = $days;

        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('leave_attachments', 'public');
            $leave->attachment = $filePath;
        }

        $leave->status = 'pending';
        $leave->save();

        return redirect()->route('leave.create')->with('success', 'Leave request submitted successfully!');
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
