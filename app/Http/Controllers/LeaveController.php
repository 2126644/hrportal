<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\LeavesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\LeaveEntitlement;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Show leave summary for logged-in employee
    public function index()
    {
        $employee = Auth::user()->employee;
        $user = Auth::user();

        // FOR CALENDAR TAB
        $allApprovedLeaves = Leave::with('employee')->where('status', 'approved')->get();
        $employeeLeaves = $allApprovedLeaves->map(function ($allApprovedLeaves) {
            return [
                'title'         => $allApprovedLeaves->employee->full_name,
                'start'         => Carbon::parse($allApprovedLeaves->start_date)->toDateString(),
                'end'           => Carbon::parse($allApprovedLeaves->end_date)->addDay()->toDateString(), // include end date
                'color'          => '#f87171',
            ];
        });

        // FOR LEAVE APPLICATION TAB
        // Total approved days used
        $usedDays = Leave::where('employee_id', $employee->employee_id)->where('status', 'approved')->sum('days');

        $pendingLeaves  = Leave::where('employee_id', $employee->employee_id)->where('status', 'pending')->count();
        $approvedLeaves = Leave::where('employee_id', $employee->employee_id)->where('status', 'approved')->count();
        $rejectedLeaves = Leave::where('employee_id', $employee->employee_id)->where('status', 'rejected')->count();
        $totalRequests  = Leave::where('employee_id', $employee->employee_id)->count();
        // count(), sum(), first() end the query; cannot chain orderBy() or get() after them

        // All leave records for the table
        $leaves = Leave::where('employee_id', $employee->employee_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // FOR LEAVE REPORT TAB

        $selectedYear = request('year', now()->year);   // get year from URL (?year=2025) or default to current year
        $selectedEmployeeName = null;

        if ($user->role === 'admin') {  // Only admins get multiple employee options
            $selectedEmployeeName = request('full_name');
            $employees = \App\Models\Employee::with('user')->orderBy('full_name')->get();
        } else {    // Not admin → only one option
            $selectedEmployeeName = $employee->full_name;   // Employee cannot switch to other names
            $employees = collect([$employee]); // Still pass 1-item collection so <select> doesn’t break
        }

        // -------------------------
        // Build reportData: days taken per leave_type per month
        // Use SUM(days) so we count days, not number of records
        // -------------------------

        // Base query
        $reportQuery = DB::table('leaves')
            ->join('employees', 'leaves.employee_id', '=', 'employees.employee_id')     
            // combine leaves with employees to filter or display based on employee details (e.g. full name)
            ->selectRaw('leaves.leave_type, MONTH(leaves.start_date) AS month, SUM(leaves.days) AS total')
            ->whereYear('leaves.start_date', $selectedYear);
            // Filter by whatever year the user selected instead of always now()->year

        // only add the name condition if the user (admin) picked one
        if ($selectedEmployeeName) {
            $reportQuery->where('employees.full_name', $selectedEmployeeName);
        }

        $leaveReport = $reportQuery
            ->groupBy('leaves.leave_type', 'month')     // grouping by leave type and month
            ->get();

        // pivot the results into [leave_type => [1=>count, 2=>count, ...]]
        $reportData = [];
        foreach ($leaveReport as $row) {
            $reportData[$row->leave_type][(int)$row->month] = (float)$row->total;
        }



        // -------------------------
        // Leave types (entitlements master)
        // Prefer the master table LeaveEntitlement. If empty, fallback to leave types present in reportData.
        // -------------------------
        $leaveTypes = LeaveEntitlement::all();
        // returns a Collection of LeaveEntitlement MODELS (objects with ->leave_type)

        if ($leaveTypes->isEmpty()) {
            // fallback: convert the keys found in reportData to objects with leave_type and full_entitlement=0
            $leaveTypeNames = array_keys($reportData);
            $leaveTypes = collect(array_map(function ($name) {
                return (object)['leave_type' => $name, 'full_entitlement' => 0];
            }, $leaveTypeNames));
        }

        // -------------------------
        // Compute final entitlements (prorated if joined this year)
        // -------------------------
        $finalEntitlements = [];
        // make sure join date is Carbon (employee model should cast it)
        $joinDate = $employee->date_joined ? Carbon::parse($employee->date_joined) : null;

        foreach ($leaveTypes as $lt) {
            // lt may be model or fallback object; unify to string and full value
            $typeName = is_object($lt) ? ($lt->leave_type ?? '') : (string)$lt;
            $full     = is_object($lt) ? ($lt->full_entitlement ?? 0) : 0;

            if ($joinDate && $joinDate->year == now()->year) {
                // prorate for first calendar year (months remaining including join month)
                $monthsLeft = 12 - $joinDate->month + 1;
                $prorated = round(($full / 12) * $monthsLeft, 2); // keep 2 decimal precision
                $finalEntitlements[$typeName] = $prorated;
            } else {
                $finalEntitlements[$typeName] = (float) $full;
            }
        }


        return view('employee.employee-leave', compact(
            'totalRequests',
            'approvedLeaves',
            'pendingLeaves',
            'rejectedLeaves',
            'usedDays',
            'leaves',
            'reportData',
            'leaveTypes',
            'employeeLeaves',
            'finalEntitlements',
            'selectedYear',
            'selectedEmployeeName',
            'employees'
        ));
    }

    public function export(Request $request)
    {
        $year = $request->input('year', now()->year);
        // collect any filters passed by users (optional)
        // $filters = $request->only(['start_date', 'end_date']);

        return Excel::download(new LeavesExport($year), 'leave_report.xlsx');
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
