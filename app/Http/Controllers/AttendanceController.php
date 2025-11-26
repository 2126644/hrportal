<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Show attendance summary for logged-in employee
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee; // null for admin if no employee record
        $query = Attendance::with('employee')->orderBy('created_at', 'desc');

        // 🔹 Admin: view all attendance
        if ($user->role_id == 2) {

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('employee', function ($qe) use ($search) {
                        $qe->where('full_name', 'like', "%{$search}%");
                    })
                        ->orWhere('employee_id', 'like', "%{$search}%");
                });
            }
        } else {
            $query->where('employee_id', $employee->employee_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('status_time_in')) {
            $query->where('status_time_in', $request->status_time_in);
        }

        if ($request->filled('status_time_out')) {
            $query->where('status_time_out', $request->status_time_out);
        }

        // Finally fetch results
        $attendances = $query->paginate(10)->withQueryString();

        // Latest record (also filter by today)
        $todayAttendance = Attendance::where('employee_id', $employee->employee_id)
            ->whereDate('date', Carbon::today())
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();
        
         // provide distinct status options to the view (move DB calls out of blade)
        $statusTimeInOptions = Attendance::select('status_time_in')->distinct()->pluck('status_time_in')->filter()->values();
        $statusTimeOutOptions = Attendance::select('status_time_out')->distinct()->pluck('status_time_out')->filter()->values();
        $statusOptions = Attendance::select('status')->distinct()->pluck('status')->filter()->values();

        $view = $user->role_id == 2 ? 'admin.admin-attendance' : 'employee.employee-attendance';

        return view($view, compact('attendances', 'todayAttendance', 'statusTimeInOptions', 'statusTimeOutOptions', 'statusOptions'));
    }

    // Punch in (mark attendance)
    public function punchIn(Request $request)
    {
        // must use these to punchin/out work
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        if (! $employee) {
            return response()->json(['message' => 'Employee record not found.'], 404);
        }

        // Office location
        $officeLat = 3.2017;
        $officeLng = 101.73256;
        $maxDistance = 1.5; // km (radius)

        $lat = $request->latitude;
        $lng = $request->longitude;

        //If within office radius → status = "on-site". Else "off-site"
        $distance = $this->calculateDistance($officeLat, $officeLng, $lat, $lng);
        $status = $distance <= $maxDistance ? 'on-site' : 'off-site';

        $now = Carbon::now();

        // Punch In (before shift 8:30/9am) → ✅ On Time
        // Punch In (8:30/9am) → ✅ Normal
        // Punch In (after 8:30/9am) → ❌ Late

        $employment = $employee->employment;

        // fallback if no employment record or times set
        $workStart = $employment && $employment->work_start_time
            ? Carbon::parse($employment->work_start_time)
            : Carbon::createFromTime(8, 30, 0);

        // Check if late
        $statusTimeIn = $now->greaterThan($workStart) ? 'Late' : 'On Time';

        $attendance = Attendance::create([
            'employee_id'   => $employee->employee_id,
            'date'          => $now->toDateString(),
            'time_in'       => $now->toTimeString(),
            'location'      => $lat . ',' . $lng,   //Saved in DB with location = "3.1400,101.6900" etc
            'status'        => $status,
            'status_time_in' => $statusTimeIn,
        ]);

        return response()->json([
            'id'   => $attendance->id,
            'time' => $attendance->date . ' ' . $attendance->time_in,  //show both date and time
            'status_time_in' => $statusTimeIn,
            'status'  => $status,
            //to differentiate punch in and punch out:
            'success' => true,
            'action'  => 'punchIn'
        ]);
    }

    // Helper function to calculate distance in KM
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in km
    }

    public function punchOut(Request $request)
    {
        // must use these to punchin/out work
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        if (! $employee) {
            return response()->json(['message' => 'Employee record not found.'], 404);
        }
        // just use this doesnt work: $employee = Auth::user()->employee;

        $now = Carbon::now();

        // Find today's attendance
        $attendance = Attendance::where('employee_id', $employee->employee_id)
            ->whereDate('date', $now->toDateString())
            ->first();

        if (! $attendance) {
            return response()->json(['message' => 'No punch in record found for today.'], 400);
        }

        if ($attendance->time_out) {
            return response()->json(['success' => false, 'message' => 'You already punched out today.']);
        }

        // // Check location again
        // $officeLat = 3.2017;
        // $officeLng = 101.73256;
        // $maxDistance = 1; // km

        // $lat = $request->latitude;
        // $lng = $request->longitude;

        // $distance = $this->calculateDistance($officeLat, $officeLng, $lat, $lng);
        // $status = $distance <= $maxDistance ? 'on-site' : 'off-site';

        // Punch Out (before 5:30pm) → ❌ Early Leave
        // Punch Out (5:30pm) → ✅ Normal
        // Punch Out (after 5:30pm) → ⭐ Overtime

        $employment = $employee->employment;

        // fallback if no employment record or times set
        $workEnd = $employment && $employment->work_end_time
            ? Carbon::parse($employment->work_end_time)
            : Carbon::createFromTime(17, 30, 0);

        // Check if early leave
        $statusTimeOut = $now->lt($workEnd) ? 'Early Leave' : 'On Time';

        $attendance->update([
            'time_out'       => $now->toTimeString(),
            'status_time_out' => $statusTimeOut
            // 'status'         => $status, // override with punch out location
        ]);

        return response()->json([
            'id'   => $attendance->id,
            'time' => $attendance->date . ' ' . $attendance->time_out,
            'status_time_out' => $statusTimeOut,
            //to differentiate punch in and punch out:
            'success' => true,
            'action'  => 'punchOut'
        ]);
    }

    // Export attendance to Excel
    public function export(Request $request)
    {
        // get year filter from request, default to current year
        $year = $request->input('year', now()->year);
        // collect any filters passed by users (optional)
        // $filters = $request->only(['start_date', 'end_date']);

        return Excel::download(new AttendanceExport($year), 'attendance_report.xlsx');
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
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'late_reason' => 'nullable|string|max:255',
            'early_leave_reason' => 'nullable|string|max:255',
        ]);

        $attendance->late_reason = $request->late_reason;
        $attendance->early_leave_reason = $request->early_leave_reason;
        $attendance->save();

        return redirect()->back()->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Allow employee to cancel their pending time slip request.
     */
    public function destroyTimeSlip(Attendance $attendance)
    {
        $employee = Auth::user()->employee;
        if (! $employee || $attendance->employee_id !== $employee->employee_id) {
            abort(403, 'Unauthorized.');
        }

        // only act when time slip exists & is pending
        if (! $attendance->time_slip_start || $attendance->time_slip_status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending time slip requests can be cancelled.');
        }

        // safer: cancel time slip fields instead of deleting attendance row
        $attendance->update([
            'time_slip_start'  => null,
            'time_slip_end'    => null,
            'time_slip_reason' => null,
            'time_slip_status' => null,
        ]);

        return redirect()->back()->with('success', 'Time slip request cancelled.');
    }

    public function requestTimeSlip(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        $request->validate([
            'time_slip_start' => 'required|date_format:H:i',
            'time_slip_end'   => 'required|date_format:H:i|after:time_slip_start',
            'time_slip_reason' => 'required|string|max:255',
        ]);

        // Limit max duration to 2 hours
        $start = \Carbon\Carbon::createFromFormat('H:i', $request->time_slip_start);
        $end = \Carbon\Carbon::createFromFormat('H:i', $request->time_slip_end);
        if ($end->diffInMinutes($start) > 120) {
            return redirect()->back()->withErrors(['time_slip_end' => 'Time slip cannot exceed 2 hours']);
        }

        $todayAttendance = Attendance::firstOrCreate(
            ['employee_id' => $employee->employee_id, 'date' => now()->toDateString()]
        );

        $todayAttendance->update([
            'time_slip_start' => $request->time_slip_start,
            'time_slip_end' => $request->time_slip_end,
            'time_slip_reason' => $request->time_slip_reason,
            'time_slip_status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Time slip requested successfully.');
    }

    public function approveTimeSlip(Request $request, Attendance $attendance)
    {
        $request->validate([
            'action' => 'required|in:approved,rejected'
        ]);

        $attendance->time_slip_status = $request->action;
        $attendance->save();

        return redirect()->back()->with('success', 'Time slip has been approved.');
    }

    public function pendingTimeSlips()
    {
        $pendingTimeSlips = Attendance::with('employee')
            ->whereNotNull('time_slip_start')
            ->where('time_slip_status', 'pending')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.pending-time-slips', compact('pendingTimeSlips'));
    }
}
