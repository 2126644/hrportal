<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TwoFactorController;
use App\Models\Employee;

// Home page
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/holidays', function () {
    $icsUrl = "https://calendar.google.com/calendar/ical/en.malaysia%23holiday%40group.v.calendar.google.com/public/basic.ics";
    $icsData = Http::get($icsUrl)->body();
    return response($icsData)->header('Content-Type', 'text/calendar');
});

Route::get('/dashboard', function () {
    if (Auth::check()) {
        // If the user is already logged in
        if (Auth::user()->role_id === 2) {
            // Send admins to their dashboard
            return redirect()->route('admin.dashboard');
        } else {
            // send students to their dashboard
            return redirect()->route('employee.dashboard');
        }
    }
    // otherwise send guests to login
    return redirect()->route('login');
})->middleware('auth')->name('dashboard');

// Two-Factor Routes

// Show 2FA challenge page
Route::get('/two-factor-challenge', [TwoFactorController::class, 'index'])->name('two-factor.login');
// Handle submitted code
Route::post('/two-factor-challenge', [TwoFactorController::class, 'store'])->name('two-factor.store');

//Route for employee
Route::middleware(['auth'])->group(function () {
    Route::get('employee-dashboard', [EmployeeController::class, 'showDashboardForLoggedInUser'])->name('employee.dashboard');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('employee.attendance');
    Route::post('/attendance/punch-in', [AttendanceController::class, 'punchIn'])->name('attendance.punchIn');
    Route::post('/attendance/punch-out', [AttendanceController::class, 'punchOut'])->name('attendance.punchOut');
    Route::get('/attendance/report', [AttendanceController::class, 'export'])->name('attendance.export');
    // Route::post('/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    
    Route::get('/leave', [LeaveController::class, 'index'])->name('employee.leave');
    Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/leave/apply', [LeaveController::class, 'create'])->name('leave.create');
    // Route::get('/leave/{id}', [LeaveController::class, 'show'])->name('leave.show');
    // Route::get('/leave/{leave}/edit', [LeaveController::class, 'edit'])->name('leave.edit');
    // Route::put('/leave/{leave}', [LeaveController::class, 'update'])->name('leave.update');
    // Route::delete('/leave/{id}', [LeaveController::class, 'destroy'])->name('leave.destroy');
    Route::get('/leave/report', [LeaveController::class, 'export'])->name('leave.export');

    Route::get('/tasks', [TaskController::class, 'index'])->name('employee.task');
    Route::post('/task', [TaskController::class, 'store'])->name('task.store');
    Route::get('/task/create', [TaskController::class, 'create'])->name('task.create');
    // Route::get('/task/{id}', [TaskController::class, 'show'])->name('task.show');
    // Route::get('/task/{task}/edit', [TaskController::class, 'edit'])->name('task.edit');
    Route::put('/task/{task}', [TaskController::class, 'update'])->name('task.update');
    // Route::delete('/task/{id}', [TaskController::class, 'destroy'])->name('task.destroy');

    Route::get('/event', [EventController::class, 'index'])->name('employee.event');
    Route::post('/event', [EventController::class, 'store'])->name('event.store');
    Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::get('/event/{id}', [EventController::class, 'show'])->name('event.show');
    Route::get('/event/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{id}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{id}', [EventController::class, 'destroy'])->name('event.destroy');

    Route::get('/profile/show/{employee?}', [EmployeeController::class, 'show'])->name('profile.show');
    Route::get('/profile/settings', [EmployeeController::class, 'settings'])->name('profile.settings');

    Route::get('/profile/edit/personal/{employee}', [EmployeeController::class, 'editPersonal'])->name('profile.editPersonal');
    Route::put('/profile/update/personal/{employee}', [EmployeeController::class, 'updatePersonal'])->name('profile.updatePersonal');

    Route::get('/profile/edit/employment/{employee}', [EmployeeController::class, 'editEmployment'])->name('profile.editEmployment');
    Route::put('/profile/update/employment/{employee}', [EmployeeController::class, 'updateEmployment'])->name('profile.updateEmployment');

});

//Route for admin
Route::middleware(['auth'])->group(function () {
    Route::get('admin-dashboard', [AdminController::class, 'showDashboardForLoggedInAdmin'])->name('admin.dashboard');

    Route::get('/admin/employee', [AdminController::class, 'employee'])->name('admin.employee');

    Route::get('/admin/attendance', [AttendanceController::class, 'index'])->name('admin.attendance');

    Route::get('/admin/leave', [LeaveController::class, 'index'])->name('admin.leave');

    Route::get('/admin/tasks', [TaskController::class, 'index'])->name('admin.task');

    Route::get('/admin/event', [EventController::class, 'index'])->name('admin.event');

    // Route::get('/setting', [SettingController::class, 'index'])->name('admin.setting');
});