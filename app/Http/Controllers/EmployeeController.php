<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->take(5)
            ->get();

        // Attendance Summary Card
        $attendanceRecords = Attendance::where('employee_id', $employee->employee_id)->get();

        $daysPresent = optional($attendanceRecords->whereIn('status', ['on-site','off-site']))->count();
        $daysAbsent  = $attendanceRecords->where('status', 'leave')->count();
        $lastPunchIn = optional($attendanceRecords->whereIn('status', ['on-site','off-site'])->last())->created_at;

        $attendance = [
            'days_present' => $daysPresent,
            'days_absent' => $daysAbsent,
            'last_punch_in' => $lastPunchIn ? $lastPunchIn->format('d M Y h:i A') : '-',
        ];

        $todayAttendance = Attendance::where('employee_id', $employee->employee_id)->whereDate('date', Carbon::today())->first();

        // Task Summary Card
        $taskRecords = Task::where('employee_id', $employee->employee_id)->get();

        $pendingTask = optional($taskRecords->whereIn('status', ['to-do','in-progress', 'in-review']))->count();
        $completedTask  = $taskRecords->where('status', 'completed')->count();
        $overdueTask = $taskRecords->where('status', '!=', 'completed')->where('due_date', '<', now())->count();

        $task = [
            'pending_task' => $pendingTask,
            'completed_task' => $completedTask,
            'overdue_task' => $overdueTask
        ];

        $tasksByStatus = Task::where('employee_id', $employee->employee_id)
            ->get()
            ->groupBy('status');

        return view('employee.employee-dashboard', compact('employee', 'upcomingEvents', 'todayAttendance', 'attendance', 'task', 'tasksByStatus'));

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
    public function show()
    {
        $employee = Auth::user()->employee;

        return view('profile.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $employee = Auth::user()->employee;

        return view('profile.settings', compact('employee'));
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
