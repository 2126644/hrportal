<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Task;
use App\Models\Leave;
use Carbon\Carbon;
use App\Models\Employment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LeaveEntitlement;

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
        $pendingLeaves = Leave::where('status', 'pending')->count();

        // Active Tasks (tasks that are not completed)
        $activeTasks = Task::whereIn('status', ['to-do', 'in-progress', 'in-review'])->count();

        // Today's attendance breakdown
        $absentToday = $totalEmployees - $presentToday;

        // Get all employees for the detailed table
        $allEmployees = Employee::with('employment')
            ->get()
            ->map(function ($employee) {
                return [
                    'employee_id' => $employee->employee_id,
                    'full_name' => $employee->full_name,
                    'department' => $employee->employment->department ?? '-',
                    'position' => $employee->employment->position ?? '-',
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

        return view('admin.admin-dashboard', compact(
            'totalEmployees',
            'presentToday',
            'pendingLeaves',
            'activeTasks',
            'absentToday',
            'recentActivities',
            'allEmployees',
            'todayAttendance'
        ));
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Recent attendance punches (last 5)
        $recentPunches = Attendance::with('employee')
            ->whereDate('date', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->take(5)
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

    public function showDashboardForLoggedInAdmin()
    {
        return $this->dashboard();
    }

    public function employee(Request $request)
    {
        $query = Employee::with('employment');

        // Search by name or employee_id
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->whereHas('employment', function ($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        // Filter by status
        if ($request->filled('employment_status')) {
            $query->whereHas('employment', function ($q) use ($request) {
                $q->where('employment_status', $request->status);
            });
        }

        // Filter by branch
        if ($request->filled('company_branch')) {
            $query->whereHas('employment', function ($q) use ($request) {
                $q->where('company_branch', $request->company_branch);
            });
        }

        if ($request->filled('date_joined')) {
            $query->whereDate('employment', function ($q) use ($request) {
                $q->where('date_joined', '>=', $request->date_joined);
            });
        }

        // Sort and paginate
        $employees = $query->orderBy('created_at', 'desc')->paginate(10);

        $totalEmployees = Employee::count();
        $activeToday = Attendance::whereDate('date', Carbon::today())->distinct('employee_id')->count();
        $onLeave = Leave::whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->where('status', 'approved')
            ->count();
        $newThisMonth = Employment::whereMonth('date_joined', Carbon::now()->month)->count();

        return view('admin.admin-employee', compact(
            'employees',
            'totalEmployees',
            'activeToday',
            'onLeave',
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
}
