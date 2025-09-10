<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Show attendance summary for logged-in employee
    public function index()
    {
        $employee = Auth::user()->employee;

        $attendanceRecords = Attendance::where('employee_id', $employee->employee_id)->get();

        $daysPresent = $attendanceRecords->where('status', 'present')->count();
        $daysAbsent  = $attendanceRecords->where('status', 'absent')->count();
        $lastPunchIn = $attendanceRecords->where('status', 'present')->last()?->created_at;

        return response()->json([
            'days_present' => $daysPresent,
            'days_absent'  => $daysAbsent,
            'last_punch_in' => $lastPunchIn ? $lastPunchIn->toDateTimeString() : '-',
        ]);
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

        // Punch In (before 8:30am) → ✅ On Time
        // Punch In (8:30am) → ✅ Normal
        // Punch In (after 8:30am) → ❌ Late

        $workStart = Carbon::createFromTime(8, 30, 0); // 08:30 AM

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
            'time' => $attendance->created_at->toDateTimeString(),  //show both date and time
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

        // Official work end time (5:30pm)
        $workEnd = Carbon::createFromTime(17, 30, 0);

        // Check if early leave
        $statusTimeOut = $now->lt($workEnd) ? 'Early Leave' : 'On Time';

        $attendance->update([
            'time_out'       => $now->toTimeString(),
            'status_time_out' => $statusTimeOut
            // 'status'         => $status, // override with punch out location
        ]);

        return response()->json([
            'time' => $attendance->created_at->toDateTimeString(),  //show both date and time
            'status_time_out' => $statusTimeOut,
            //to differentiate punch in and punch out:
            'success' => true,
            'action'  => 'punchOut'
        ]);
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
