<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Leave;
use App\Models\Attendance;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
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

    public function requests(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee; // null for admin if no employee record

        $employeeId = optional(Auth::user()->employee)->employee_id;

        $leaveTypes = Leave::select('leave_type')->distinct()->pluck('leave_type');

        // --- Leave Requests with filters ---
        $leavesQuery = Leave::with('employee')
            ->where('status', 'pending')
            ->whereHas('employee.approvers', function ($q) use ($employeeId) {
                $q->where('approver_id', $employeeId)
                    ->whereColumn('employment_approvers.level', 'leaves.approval_level');
            })
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $leavesQuery->whereHas('employee', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('leave_type')) {
            $leavesQuery->where('leave_type', $request->leave_type);
        }

        if ($request->filled('start_date')) {
            $leavesQuery->whereDate('start_date', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $leavesQuery->whereDate('end_date', $request->end_date);
        }

        if ($request->filled('created_at')) {
            $leavesQuery->whereDate('created_at', $request->created_at);
        }

        $pendingLeaves = $leavesQuery->get();

        // --- Time Slip Requests with filters ---
        $timeSlipsQuery = Attendance::with('employee')
            ->whereNotNull('time_slip_start')
            ->where('time_slip_status', 'pending')
            ->whereHas('employee.approvers', function ($q) use ($employeeId) {
                $q->where('approver_id', $employeeId);
            })
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $timeSlipsQuery->whereHas('employee', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $timeSlipsQuery->whereDate('date', $request->date);
        }

        $pendingTimeSlips = $timeSlipsQuery->get();

        return view('employee.approver-request', compact(
            'leaveTypes',
            'pendingLeaves',
            'pendingTimeSlips'
        ));
    }

    public function myRequests(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee; // null for admin if no employee record

        $leaveTypes = Leave::select('leave_type')->distinct()->pluck('leave_type');

        // --- Leave Requests with filters ---
        $leavesQuery = Leave::with('employee')
            ->where('employee_id', $employee->employee_id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $leavesQuery->whereHas('employee', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('leave_type')) {
            $leavesQuery->where('leave_type', $request->leave_type);
        }

        if ($request->filled('start_date')) {
            $leavesQuery->whereDate('start_date', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $leavesQuery->whereDate('end_date', $request->end_date);
        }

        if ($request->filled('created_at')) {
            $leavesQuery->whereDate('created_at', $request->created_at);
        }

        $pendingLeaves = $leavesQuery->get();

        // --- Time Slip Requests with filters ---
        $timeSlipsQuery = Attendance::with('employee')
            ->where('employee_id', $employee->employee_id)
            ->whereNotNull('time_slip_start')
            ->where('time_slip_status', 'pending')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $timeSlipsQuery->whereHas('employee', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $timeSlipsQuery->whereDate('date', $request->date);
        }

        $pendingTimeSlips = $timeSlipsQuery->get();

        return view('employee.employee-request', compact(
            'leaveTypes',
            'pendingLeaves',
            'pendingTimeSlips'
        ));
    }

    public function adminRequests(Request $request)
    {
        $leaveTypes = Leave::select('leave_type')->distinct()->pluck('leave_type');

        // --- Leave Requests with filters ---
        $leavesQuery = Leave::with('employee')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $leavesQuery->whereHas('employee', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('leave_type')) {
            $leavesQuery->where('leave_type', $request->leave_type);
        }

        if ($request->filled('start_date')) {
            $leavesQuery->whereDate('start_date', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $leavesQuery->whereDate('end_date', $request->end_date);
        }

        if ($request->filled('created_at')) {
            $leavesQuery->whereDate('created_at', $request->created_at);
        }

        $pendingLeaves = $leavesQuery->get();

        // --- Time Slip Requests with filters ---
        $timeSlipsQuery = Attendance::with('employee')
            ->whereNotNull('time_slip_start')
            ->where('time_slip_status', 'pending')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $timeSlipsQuery->whereHas('employee', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $timeSlipsQuery->whereDate('date', $request->date);
        }

        $pendingTimeSlips = $timeSlipsQuery->get();

        return view('admin.admin-request', compact(
            'pendingLeaves',
            'pendingTimeSlips',
            'leaveTypes'
        ));
    }
}
