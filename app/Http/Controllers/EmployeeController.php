<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Employment;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Leave;
use App\Models\Announcement;

class EmployeeController extends Controller
{
    public function showDashboardForLoggedInUser()
    {
        $employee = Auth::user()->employee;
        if (! $employee) {
            abort(404);
        }

        if (!$employee) {
            return redirect()->route('logout')->withErrors(['error' => 'Employee profile not found!']);
        }

        // Fetch upcoming events (adjust your table column names)
        $upcomingEvents = Event::where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        // Attendance Summary Card
        $attendanceRecords = Attendance::where('employee_id', $employee->employee_id)->get();

        $daysPresent = optional($attendanceRecords->whereIn('status', ['on-site', 'off-site']))->count();
        $daysAbsent  = $attendanceRecords->where('status', 'leave')->count();
        $lastPunchIn = optional($attendanceRecords->whereIn('status', ['on-site', 'off-site'])->last())->created_at;

        $attendance = [
            'days_present' => $daysPresent,
            'days_absent' => $daysAbsent,
            'last_punch_in' => $lastPunchIn ? $lastPunchIn->format('d M Y h:i A') : '-',
        ];

        // Task Summary Card
        $taskRecords = Task::where('assigned_to', $employee->employee_id)->get();

        // Group tasks by status for display
        $tasksByStatus = [
            'to-do' => $taskRecords->where('task_status', 'to-do'),
            'in-progress' => $taskRecords->where('task_status', 'in-progress'),
            'in-review' => $taskRecords->where('task_status', 'in-review'),
            'completed' => $taskRecords->where('task_status', 'completed'),
        ];

        $pendingTask = optional($taskRecords->whereIn('task_status', ['to-do', 'in-progress', 'in-review', 'to-review']))->count();
        $completedTask  = $taskRecords->where('task_status', 'completed')->count();
        $overdueTask = $taskRecords->where('task_status', '!=', 'completed')->where('due_date', '<', now())->count();
        $totalTask = $taskRecords->where('assigned_to', $employee->employee_id)->count();

        $task = [
            'pending_task' => $pendingTask,
            'completed_task' => $completedTask,
            'overdue_task' => $overdueTask,
            'total_task' => $totalTask,
        ];

        $todayAttendance = Attendance::where('employee_id', $employee->employee_id)->whereDate('date', Carbon::today())->first();

        // Leave Summary Card
        $leaveRecords = Leave::where('employee_id', $employee->employee_id)->get();

        // Pending leave requests
        $pendingLeave = $leaveRecords->where('status', 'pending')->count();

        // Upcoming approved leave
        $upcomingLeaveRecords = Leave::where('employee_id', $employee->employee_id)
            ->where('status', 'approved')
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get();

        $upcomingLeaveCount = $upcomingLeaveRecords->count();

        // Get the next leave date (if any)
        $nextLeaveDate = $upcomingLeaveRecords->first()->start_date ?? null;

        $leave = [
            'pending_leave' => $pendingLeave,
            'upcoming_leave' => $upcomingLeaveCount,
            'next_leave_date' => $nextLeaveDate
                ? Carbon::parse($nextLeaveDate)->format('d M Y')
                : '-',
        ];

        // Profile Summary
        $profile = [
            'full_name' => $employee->full_name,
            'employee_id' => $employee->employee_id,
            'position' => $employee->position->position ?? 'N/A',
            'company_branch' => $employee->employment->company_branch ?? 'N/A',
        ];

        // recent announcements (latest 5)
        $announcements = Announcement::orderBy('created_at', 'desc')->take(3)->get();

        return view('employee.employee-dashboard', compact(
                'employee',
                'upcomingEvents',
                'todayAttendance',
                'attendance',
                'task',
                'taskRecords',
                'tasksByStatus',
                'leave',
                'profile',
                'announcements'
            )
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with('user')->get();
        return response()->json($employees);
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
    public function show(Employee $employee = null)
    {
        // If no employee param provided, use the logged in user's employee
        if (! $employee) {
            $employee = Auth::user()->employee;
            if (! $employee) {
                abort(404);
            }
        }

        // Admin can view anyone; regular employee can only view their own profile
        if (Auth::user()->role_id !== 2) {
            if ($employee->employee_id != Auth::user()->employee->employee_id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $employment = $employee->employment;

        return view('profile.show', compact('employee', 'employment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function settings()
    {
        $employee = Auth::user()->employee;

        return view('profile.settings', compact('employee'));
    }

    public function editPersonal(Employee $employee)
    {
        // Admin can view anyone; employee sees themselves
        if (Auth::user()->role_id !== 2) {
            // Ensure employee can only edit their own profile
            if ($employee->employee_id != Auth::user()->employee->employee_id) {
                abort(403, 'Unauthorized action.');
            }
        }

        return view('profile.editprofile', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePersonal(Request $request, Employee $employee)
    {
        // Admin can update anyone; employee can only update themselves
        if (Auth::user()->role_id !== 2) {
            if ($employee->employee_id != Auth::user()->employee->employee_id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $validated = $request->validate([
            'full_name'         => 'required|string|max:255',
            'email'             => 'required|string',
            'phone_number'      => 'required|string|max:20',
            'address'           => 'required|string|max:500',
            'gender'            => 'required|string|max:10',
            'birthday'          => 'required|date',
            'marital_status'    => 'required|string|max:50',
            'nationality'       => 'required|string|max:50',
            'emergency_contact' => 'required|string|max:255',
            'ic_number'         => 'required|string|max:20',
            'highest_education' => 'required|string|max:100',
            'education_institution' => 'required|string|max:255',
            'graduation_year'   => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $employee->update($validated);
        return redirect()->route('profile.show', $employee->employee_id)->with('success', 'Profile updated successfully!');
    }

    public function editEmployment(Employee $employee)
    {
        // Only admin can edit employment details
        if (Auth::user()->role_id !== 2) {
            abort(403, 'Unauthorized action.');
        }

        $employment = $employee->employment ?? new Employment(['employee_id' => $employee->employee_id]);

        return view('profile.editemployment', compact('employee', 'employment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateEmployment(Request $request, Employee $employee)
    {
        // Only admin can update employment details
        if (Auth::user()->role_id !== 2) {
            abort(403, 'Unauthorized action.');
        }

        $employment = $employee->employment;

        $validated = $request->validate([
            'employee_id'        => 'required|exists:employees,employee_id',
            'employment_type'    => 'required|string|max:100',
            'employment_status'  => 'required|string|max:100',
            'company_branch'     => 'required|string|max:100',
            'report_to'          => 'nullable|exists:employees,employee_id',
            'department'         => 'required|string|max:100',
            'position'           => 'required|string|max:100',
            'date_joined'       => 'required|date',
            'probation_start'    => 'nullable|date',
            'probation_end'      => 'nullable|date',
            'suspended_start'    => 'nullable|date',
            'suspended_end'      => 'nullable|date',
            'resigned_date'      => 'nullable|date',
            'termination_date'   => 'nullable|date',
            'work_start_time'    => 'nullable|date_format:H:i',
            'work_end_time'      => 'nullable|date_format:H:i',
        ]);

        $employment->update($validated);

        return redirect()->route('profile.show', $employee->employee_id)->with('success', 'Employment details updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function requests()
    {
        $employee = Auth::user()->employee;

        // --- Leave Requests ---
        $pendingLeaves = Leave::with('$employee_id')
            ->where('employee_id', $employee->employee_id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // --- Time Slip Requests ---
        $pendingTimeSlips = Attendance::with('employee')
            ->where('employee_id', $employee->employee_id)
            ->whereNotNull('time_slip_start')
            ->where('time_slip_status', 'pending')
            ->orderBy('date', 'desc')
            ->get();

        return view('employee.employee-request', compact(
            'pendingLeaves',
            'pendingTimeSlips'
        ));
    }
}
