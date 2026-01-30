<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Task;
use App\Models\Leave;
use App\Models\Announcement;
use App\Models\Department;
use App\Models\Role;
use Carbon\Carbon;
use App\Models\Employment;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use App\Actions\Fortify\CreateNewUser;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Total Employees
        $totalEmployees = Employee::count();

        // Present Today (employees who punched in today)
        $presentToday = Attendance::whereDate('date', Carbon::today())
            ->distinct('employee_id')
            ->count('employee_id');

        // Pending Leave Requests
        $pendingLeaves = Leave::where('leave_status', 'pending')->count();

        // Active Tasks (tasks that are not completed)
        $activeTasks = Task::whereIn('task_status', ['to-do', 'in-progress', 'in-review'])->count();

        // Today's attendance breakdown
        $absentToday = $totalEmployees - $presentToday;

        // Get all employees for the detailed table
        $allEmployees = Employee::with('employment.department')
            ->get()
            ->map(function ($employee) {
                return [
                    'employee_id' => $employee->employee_id,
                    'full_name' => $employee->full_name,
                    'department' => $employee->employment?->department?->department_name ?? '-',
                    'position' => $employee->employment?->position ?? '-',
                ];
            });

        // Get today's attendance with employee details
        $todayAttendance = Attendance::with('employee')
            ->whereDate('date', Carbon::today())
            ->get()
            ->map(function ($attendance) {
                return [
                    'employee_id' => $attendance->employee_id,
                    'time_in' => $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('g:i A') : null,
                    'time_out' => $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('g:i A') : null,
                    'status_time_in' => $attendance->status_time_in,
                    'employee_name' => $attendance->employee->full_name ?? 'Unknown'
                ];
            });

        // Recent Activities (last 10 activities across different models)
        $recentActivities = $this->getRecentActivities();

        // recent announcements (latest 5)
        $announcements = Announcement::orderBy('created_at', 'desc')->take(5)->get();

        // --- Leave Counts ---
        $totalPendingLeaves   = Leave::where('leave_status', 'pending')->count();
        $totalApprovedLeaves  = Leave::where('leave_status', 'approved')->count();
        $totalRejectedLeaves  = Leave::where('leave_status', 'rejected')->count();

        // --- Time Slip Counts ---
        $totalPendingTimeSlips   = Attendance::whereNotNull('time_slip_start')
            ->where('time_slip_status', 'pending')->count();

        $totalApprovedTimeSlips  = Attendance::whereNotNull('time_slip_start')
            ->where('time_slip_status', 'approved')->count();

        $totalRejectedTimeSlips  = Attendance::whereNotNull('time_slip_start')
            ->where('time_slip_status', 'rejected')->count();

        // --- Combined (ALL pending/approved/rejected) ---
        $totalPending   = $totalPendingLeaves + $totalPendingTimeSlips;
        $totalApproved  = $totalApprovedLeaves + $totalApprovedTimeSlips;
        $totalRejected  = $totalRejectedLeaves + $totalRejectedTimeSlips;

        $timeSlips = Attendance::with('employee')
            ->whereNotNull('time_slip_start')
            ->where('time_slip_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($ts) {
                return [
                    'employee'        => $ts->employee->full_name,
                    'type'            => 'Time Slip',
                    'status'          => $ts->time_slip_status,
                    'submitted_date'  => $ts->created_at->format('d M Y g:i A'),
                    'duration'        => $ts->time_slip_start->format('g:i A') . ' - ' . $ts->time_slip_end->format('g:i A'),
                    'is_time_slip'    => true,
                    'timestamp'       => $ts->created_at, // For sorting
                ];
            });

        $leaves = Leave::with('employee')
            ->where('leave_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($lv) {
                return [
                    'employee'        => $lv->employee->full_name,
                    'type'            => ucfirst($lv->leave_type) . ' Leave',
                    'status'          => $lv->leave_status,
                    'submitted_date'  => $lv->created_at->format('d M Y g:i A'),
                    'duration'        => $lv->start_date->format('d M Y') . ' → ' . $lv->end_date->format('d M Y'),
                    'is_time_slip'    => false,
                    'timestamp'       => $lv->created_at, // For sorting
                ];
            });

        $recentRequests = collect()
            ->merge($timeSlips)
            ->merge($leaves)
            ->sortByDesc('timestamp')
            ->take(5)
            ->values();

        return view('admin.admin-dashboard', compact(
            'totalEmployees',
            'presentToday',
            'pendingLeaves',
            'activeTasks',
            'absentToday',
            'recentActivities',
            'allEmployees',
            'todayAttendance',
            'recentRequests',
            'announcements',
            'totalApproved',
            'totalPending',
            'totalRejected'
        ));
    }

    public function showDashboardForLoggedInAdmin()
    {
        return $this->dashboard();
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Recent attendance punches (last 5)
        $recentPunches = Attendance::with('employee')
            ->whereDate('date', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        foreach ($recentPunches as $punch) {
            $activities[] = [
                'icon' => $punch->time_in ? 'person-check' : 'person-x',
                'title' => $punch->employee->full_name ?? 'Employee',
                'description' => $punch->time_in ? 'Punched in' : 'Punched out',
                'time' => $punch->created_at->diffForHumans()
            ];
        }

        // Recent leave requests (last 3)
        $recentLeaves = Leave::with('employee')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        foreach ($recentLeaves as $leave) {
            $activities[] = [
                'icon' => 'calendar-plus',
                'title' => $leave->employee->full_name ?? 'Employee',
                'description' => 'Applied for ' . $leave->leave_type . ' leave',
                'time' => $leave->created_at->diffForHumans()
            ];
        }

        // Sort all activities by time and take latest 8
        usort($activities, function ($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 8);
    }

    public function employee(Request $request)
    {
        $query = Employee::with('employment.department');

        // Search by name or employee_id
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Filter by department NAME
        if ($request->filled('department_name')) {
            $query->whereHas('employment.department', function ($q) use ($request) {
                $q->where('department_name', $request->department_name);
            });
        }

        // Filter by status
        if ($request->filled('employment_status')) {
            $query->whereHas('employment', function ($q) use ($request) {
                $q->where('employment_status', $request->employment_status);
            });
        }

        // Filter by branch
        if ($request->filled('company_branch')) {
            $query->whereHas('employment', function ($q) use ($request) {
                $q->where('company_branch', $request->company_branch);
            });
        }

        // Filter by date of employment
        if ($request->filled('date_of_employment')) {
            $query->whereHas('employment', function ($q) use ($request) {
                $q->whereDate('date_of_employment', $request->date_of_employment);
            });
        }

        // Card filters
        // filter for employments ending in next 30 days
        if ($request->filter === 'ending') {
            $query->whereHas('employment', function ($q) {
                $today = now();
                $next30Days = now()->addDays(30);

                $q->where(function ($sub) use ($today, $next30Days) {
                    $sub->whereBetween('contract_end', [$today, $next30Days])
                        ->orWhereBetween('termination_date', [$today, $next30Days])
                        ->orWhereBetween('last_working_day', [$today, $next30Days])
                        ->orWhereBetween('probation_end', [$today, $next30Days])
                        ->orWhereBetween('suspension_end', [$today, $next30Days]);
                });
            });
        }

        // filter for new employees this month
        if ($request->filter === 'new') {
            $query->whereHas('employment', function ($q) {
                $q->whereMonth('date_of_employment', now()->month)
                    ->whereYear('date_of_employment', now()->year);
            });
        }

        $employees = $query->orderBy('full_name')->get();

        // Stats for cards
        $totalEmployees = Employee::count();

        $employmentEnding = Employment::where(function ($q) {
            $today = now();
            $next30Days = now()->addDays(30);

            $q->whereBetween('contract_end', [$today, $next30Days])
                ->orWhereBetween('termination_date', [$today, $next30Days])
                ->orWhereBetween('last_working_day', [$today, $next30Days])
                ->orWhereBetween('probation_end', [$today, $next30Days])
                ->orWhereBetween('suspension_end', [$today, $next30Days]);
        })->count();


        $newThisMonth = Employment::whereMonth('date_of_employment', now()->month)
            ->whereYear('date_of_employment', now()->year)
            ->count();

        // Departments for dropdown
        $departments = Department::whereHas('employment')
            ->orderBy('department_name')
            ->pluck('department_name');

        return view('admin.admin-employee', compact(
            'employees',
            'departments',
            'totalEmployees',
            'employmentEnding',
            'newThisMonth'
        ));
    }

    public function attendance()
    {
        $attendances = Attendance::with('employee')->orderBy('date', 'desc')->paginate(10);
        return view('admin.attendance', compact('attendances'));
    }

    public function tasks()
    {
        $tasks = Task::with('assignedTo')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.tasks', compact('tasks'));
    }

    public function events()
    {
        $events = Event::orderBy('event_date', 'desc')->paginate(10);
        return view('admin.events', compact('events'));
    }

    public function createUser()
    {
        // abort_if(!auth()->user()->isAdmin(), 403);

        $roles = Role::orderBy('id')->get();

        return view('admin.admin-createemployee', compact('roles'));
    }

    public function storeUser(Request $request, CreateNewUser $creator)
    {
        $creator->create($request->all());

        return redirect()->route('admin.employee')->with('success', 'User created successfully! Please inform the user to check their email for login details.');
    }
}
