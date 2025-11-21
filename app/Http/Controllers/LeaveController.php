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
use App\Models\Employee;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Show leave summary for logged-in employee
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee; // null for admin if no employee record

        // FOR CALENDAR TAB
        $allApprovedLeaves = Leave::with('employee')->where('status', 'approved')->get();

        $colors = [
            '#f87171', // red
            '#60a5fa', // blue
            '#34d399', // green
            '#fbbf24', // yellow
            '#a78bfa', // purple
            '#f472b6', // pink
            '#38bdf8', // sky
            '#fde047', // amber
            '#6ee7b7', // emerald
            '#fca5a5', // light red
            '#93c5fd', // light blue
        ];

        $employeeColorMap = [];
        $colorIndex = 0;

        $employeeLeaves = $allApprovedLeaves->map(function ($leave) use (&$employeeColorMap, &$colorIndex, $colors) {
            $empName = $leave->employee->full_name;
            // Assign color if employee has no color yet
            if (!isset($employeeColorMap[$empName])) {
                $employeeColorMap[$empName] = $colors[$colorIndex % count($colors)];
                $colorIndex++;
            }
            return [
                'title'         => $empName,
                'start'         => Carbon::parse($leave->start_date)->toDateString(),
                'end'           => Carbon::parse($leave->end_date)->addDay()->toDateString(), // include end date
                'color'          => $employeeColorMap[$empName],  
            ];
        });

        // FOR LEAVE APPLICATION TAB
        // Total approved days used

        $query = Leave::with('employee')->orderBy('created_at', 'desc');

        // Only apply filters if the inputs exist
        if ($user->role_id === 2) {
            // Search by name or employee_id
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('employee', function ($qe) use ($search) {
                        $qe->where('full_name', 'like', "%{$search}%");
                    })
                    ->orWhere('employee_id', 'like', "%{$search}%");
                });
            }
        } else { // non-admin sees own leaves
            $query->where('employee_id', $employee->employee_id);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', $request->end_date);
        }

        if ($request->filled('applied_date')) {
            $query->whereDate('applied_date', $request->applied_date);
        }

        // Finally fetch results
        $leaves = $query->paginate(10)->withQueryString();

        $totalRequests  = $leaves->count();
        $approvedLeaves = $leaves->where('status', 'approved')->count();
        $pendingLeaves  = $leaves->where('status', 'pending')->count();
        $rejectedLeaves = $leaves->where('status', 'rejected')->count();
        $usedDays       = $leaves->where('status', 'approved')->sum('days');

        // FOR LEAVE REPORT TAB

        $selectedYear = request('year', now()->year);   // get year from URL (?year=2025) or default to current year
        $selectedEmployeeName = null;

        if ($user->role_id === 2) {  // Only admins get multiple employee options
            $selectedEmployeeName = request('full_name');
            $employees = $employees = Employee::orderBy('full_name')->get();
        } else {
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
        if ($user->role_id === 3) {
            $reportQuery->where('employees.employee_id', $employee->employee_id);
        } elseif ($selectedEmployeeName && $selectedEmployeeName !== 'all') {
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

        $view = $user->role_id == 2 ? 'admin.admin-leave' : 'employee.employee-leave';

        return view($view, compact(
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
        $leaveTypeEnum     = Leave::select('leave_type')->distinct()->pluck('leave_type');
        $leaveLengthEnum     = Leave::select('leave_length')->distinct()->pluck('leave_length');

        return view('employee.applyleave', compact('leaveTypeEnum', 'leaveLengthEnum'));
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

    public function approveLeave(Request $request, Leave $leave)
    {
        $request->validate([
            'action' => 'required|in:approved,rejected'
        ]);

        $leave->status = $request->action;
        $leave->save();

        return redirect()->back()->with('success', 'Leave has been approved.');
    }
}
