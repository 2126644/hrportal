<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Event;
use App\Models\Attendance;
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

        $todayAttendance = Attendance::where('employee_id', $employee->id)
    ->whereDate('date', Carbon::today())
    ->first();

return view('employee.employee-dashboard', compact('employee', 'upcomingEvents', 'todayAttendance'));

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
