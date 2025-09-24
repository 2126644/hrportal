<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // FOR LEAVE APPLICATION TAB
        // Total approved days used
        $usedDays = Leave::where('employee_id', $employee->employee_id)->where('status', 'approved')->sum('days');

        $leaveBalance   = 14 - $usedDays; // assuming 14 days AL
        $pendingLeaves  = Leave::where('employee_id', $employee->employee_id)->where('status', 'pending')->count();
        $approvedLeaves = Leave::where('employee_id', $employee->employee_id)->where('status', 'approved')->count();
        $rejectedLeaves = Leave::where('employee_id', $employee->employee_id)->where('status', 'rejected')->count();
        $totalRequests  = Leave::where('employee_id', $employee->employee_id)->count();
        // count(), sum(), first() end the query; cannot chain orderBy() or get() after them

        // All leave records for the table
        $leaves = Leave::where('employee_id', $employee->employee_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // FOR LEAVE REPORT TABwer
        $leaveReport = DB::table('leaves')
            ->selectRaw('leave_type, MONTH(start_date) as month, COUNT(*) as total')
            ->whereYear('start_date', now()->year)
            ->groupBy('leave_type', 'month')
            ->get();

        // pivot the results into [leave_type => [1=>count, 2=>count, ...]]
        $reportData = [];
        foreach ($leaveReport as $row) {
            $reportData[$row->leave_type][$row->month] = $row->total;
        }

        // get all leave types to ensure rows exist even if zero (still display row with zero)
        $leaveTypes = DB::table('leaves')->distinct()->pluck('leave_type');

        return view('employee.leave', compact(
            'totalRequests',
            'approvedLeaves',
            'pendingLeaves',
            'rejectedLeaves',
            'leaveBalance',
            'usedDays',
            'leaves',
            'reportData',
            'leaveTypes'
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
