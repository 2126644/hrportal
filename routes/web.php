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
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\EmploymentApproverController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\EventAttendeeController;

use App\Models\Employee;
use App\Models\User;
use Termwind\Components\Raw;

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
})->middleware('auth', 'force.password.reset')->name('dashboard');

// Two-Factor Routes

// Show 2FA challenge page
Route::get('/two-factor-challenge', [TwoFactorController::class, 'index'])->name('two-factor.login');
// Handle submitted code
Route::post('/two-factor-challenge', [TwoFactorController::class, 'store'])->name('two-factor.store');

Route::post('/notifications/read-all', function () {
    Auth::user()->unreadNotifications->markAsRead();
    return response()->json(['status' => 'ok']);
})->middleware('auth')->name('notifications.readAll');

//Route for employee
Route::middleware(['auth', 'force.password.reset'])->group(function () {
    Route::get('employee-dashboard', [EmployeeController::class, 'showDashboardForLoggedInUser'])->name('employee.dashboard');

    Route::get('/announcement', [AnnouncementController::class, 'index'])->name('announcement.index.employee');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('employee.attendance');
    Route::post('/attendance/punch-in', [AttendanceController::class, 'punchIn'])->name('attendance.punchIn');
    Route::post('/attendance/punch-out', [AttendanceController::class, 'punchOut'])->name('attendance.punchOut');
    Route::get('/attendance/report', [AttendanceController::class, 'export'])->name('attendance.export');
    // Route::post('/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');

    Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index.employee');
    Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/leave/apply', [LeaveController::class, 'create'])->name('leave.create');
    // Route::get('/leave/{id}', [LeaveController::class, 'show'])->name('leave.show');
    // Route::get('/leave/{leave}/edit', [LeaveController::class, 'edit'])->name('leave.edit');
    // Route::put('/leave/{leave}', [LeaveController::class, 'update'])->name('leave.update');
    // Route::delete('/leave/{id}', [LeaveController::class, 'destroy'])->name('leave.destroy');
    Route::get('/leave/report', [LeaveController::class, 'export'])->name('leave.export');

    Route::get('/tasks', [TaskController::class, 'index'])->name('task.index.employee');
    Route::post('/task', [TaskController::class, 'store'])->name('task.store');
    Route::get('/task/create', [TaskController::class, 'create'])->name('task.create');
    // Route::get('/task/{id}', [TaskController::class, 'show'])->name('task.show');
    // Route::get('/task/{task}/edit', [TaskController::class, 'edit'])->name('task.edit');
    Route::put('/task/{task}', [TaskController::class, 'update'])->name('task.update');
    // Route::delete('/task/{id}', [TaskController::class, 'destroy'])->name('task.destroy');

    Route::get('/projects', [ProjectController::class, 'index'])->name('project.index.employee');
    Route::post('/project', [ProjectController::class, 'store'])->name('project.store');
    Route::get('/project/create', [ProjectController::class, 'create'])->name('project.create');
    Route::put('/project/{project}', [ProjectController::class, 'update'])->name('project.update');

    Route::get('/event', [EventController::class, 'index'])->name('event.index.employee');
    Route::post('/event', [EventController::class, 'store'])->name('event.store');
    Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::get('/event/{id}', [EventController::class, 'show'])->name('event.show');
    Route::get('/event/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{id}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{id}', [EventController::class, 'destroy'])->name('event.destroy');

    Route::post('/event/{myAttendance}/attendance/confirm', [EventAttendeeController::class, 'confirm'])->name('event.attendance.confirm');
    Route::post('/event/{myAttendance}/attendance/decline', [EventAttendeeController::class, 'decline'])->name('event.attendance.decline');

    Route::get('/profile/show/{employee?}', [EmployeeController::class, 'show'])->name('profile.show');
    Route::get('/profile/settings', [EmployeeController::class, 'settings'])->name('profile.settings');
    Route::get('/profile/{id}/print', [EmployeeController::class, 'downloadProfile'])->name('profile.print');

    Route::get('/profile/edit/personal/{employee}', [EmployeeController::class, 'editPersonal'])->name('profile.editPersonal');
    Route::put('/profile/update/personal/{employee}', [EmployeeController::class, 'updatePersonal'])->name('profile.updatePersonal');

    Route::get('/profile/edit/employment/{employee}', [EmployeeController::class, 'editEmployment'])->name('profile.editEmployment');
    Route::put('/profile/update/employment/{employee}', [EmployeeController::class, 'updateEmployment'])->name('profile.updateEmployment');

    Route::post('/attendance/time-slip', [AttendanceController::class, 'requestTimeSlip'])->name('attendance.time-slip');

    Route::get('/requests', [RequestController::class, 'requests'])->name('employee.requests');
    Route::get('/myrequests', [RequestController::class, 'myRequests'])->name('employee.myrequests');

    Route::delete('/employee/leave/{leave}', [LeaveController::class, 'cancel'])->name('leave.cancel.employee');
    Route::delete('/employee/timeslip/{attendance}', [AttendanceController::class, 'destroyTimeSlip'])->name('timeslip.destroy');
});

//Route for admin
Route::middleware(['auth'])->group(function () {
    Route::get('admin-dashboard', [AdminController::class, 'showDashboardForLoggedInAdmin'])->name('admin.dashboard');

    Route::get('/admin/announcement', [AnnouncementController::class, 'index'])->name('announcement.index.admin');
    Route::get('/announcement/create', [AnnouncementController::class, 'create'])->name('announcement.create');
    Route::post('/announcement', [AnnouncementController::class, 'store'])->name('announcement.store');
    // Route::get('/announcement/{announcement}', [AnnouncementController::class, 'show'])->name('announcement.show');
    // Route::get('/announcement/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcement.edit');
    Route::put('/announcement/{announcement}', [AnnouncementController::class, 'update'])->name('announcement.update');
    // Route::delete('/announcement/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcement.destroy');

    Route::get('/admin/employee', [AdminController::class, 'employee'])->name('admin.employee');
    Route::get('/admin/employee/create', [AdminController::class, 'createUser'])->name('admin.employee.create');
    Route::post('/admin/employee', [AdminController::class, 'storeUser'])->name('admin.employee.store');
    Route::put('/admin/employee/{employee}/photo', [EmployeeController::class, 'updateProfilePhoto'])->name('admin.employee.updatePhoto');

    Route::get('/admin/attendance', [AttendanceController::class, 'index'])->name('admin.attendance');

    Route::get('/admin/leave', [LeaveController::class, 'index'])->name('leave.index.admin');

    Route::get('/admin/tasks', [TaskController::class, 'index'])->name('task.index.admin');

    Route::get('/admin/events', [EventController::class, 'index'])->name('event.index.admin');
    Route::get('/admin/event/{id}/attendees', [EventController::class, 'attendees'])->name('event.attendees');
    // Route::post('/admin/event', [EventController::class, 'edit'])->name('event.edit');

    // Route::get('/event', [EventController::class, 'index'])->name('event.index.employee');
    // Route::post('/event', [EventController::class, 'store'])->name('event.store');
    // Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    // Route::get('/event/{id}', [EventController::class, 'show'])->name('event.show');
    // Route::get('/event/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    // Route::put('/event/{id}', [EventController::class, 'update'])->name('event.update');
    // Route::delete('/event/{id}', [EventController::class, 'destroy'])->name('event.destroy');

    Route::get('/admin/projects', [ProjectController::class, 'index'])->name('project.index.admin');

    Route::get('/admin/requests', [RequestController::class, 'adminRequests'])->name('admin.request');

    // Time slip approval
    Route::post('/timeslip/{attendance}/update-status', [AttendanceController::class, 'approveTimeSlip'])->name('timeslip.updateStatus');

    // Leave approval
    Route::post('/leaves/{leave}/update-status', [LeaveController::class, 'approveLeave'])->name('leave.updateStatus');

    Route::delete('/admin/leave/{leave}', [LeaveController::class, 'destroy'])->name('leave.destroy.admin');

    Route::post('/employees/{employee}/approvers', [EmploymentApproverController::class, 'store'])->name('employees.approvers.store');

    Route::get('/admin/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/admin/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/employees/all', [EmployeeController::class, 'getAllEmployees'])->name('employees.all');
});
