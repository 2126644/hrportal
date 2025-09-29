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
    return view('welcome');
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

//Route for DATABASE
Route::middleware(['auth'])->group(function () {
    Route::get('employee-dashboard', [EmployeeController::class, 'showDashboardForLoggedInUser'])->name('employee.dashboard');
    Route::get('admin-dashboard', [AdminController::class, 'showDashboardForLoggedInAdmin'])->name('admin.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('employee.attendance');
    Route::post('/attendance/punch-in', [AttendanceController::class, 'punchIn'])->name('attendance.punchIn');
    Route::post('/attendance/punch-out', [AttendanceController::class, 'punchOut'])->name('attendance.punchOut');
    
    Route::get('/leave', [LeaveController::class, 'index'])->name('employee.leave');
    Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/leave/apply', [LeaveController::class, 'create'])->name('leave.create');
    Route::get('/leave/report', [LeaveController::class, 'export'])->name('leave.export');

    Route::get('/tasks', [TaskController::class, 'index'])->name('employee.task');
    Route::post('/task', [TaskController::class, 'store'])->name('task.store');
    Route::get('/task/create', [TaskController::class, 'create'])->name('task.create');

    Route::get('/event', [EventController::class, 'index'])->name('employee.event');
    Route::post('/event', [EventController::class, 'store'])->name('event.store');
    Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::get('/event/{id}', [EventController::class, 'show'])->name('event.show');
    // Route::get('/event/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    // Route::put('/event/{id}', [EventController::class, 'update'])->name('event.update');
    // Route::delete('/event/{id}', [EventController::class, 'destroy'])->name('event.destroy');
});

Route::get('/holidays', function () {
    $icsUrl = "https://calendar.google.com/calendar/ical/en.malaysia%23holiday%40group.v.calendar.google.com/public/basic.ics";
    $icsData = Http::get($icsUrl)->body();
    return response($icsData)->header('Content-Type', 'text/calendar');
});

// Routes for old website

// Route::get('admin-courses', [AdminController::class, 'showCoursesList'])->name('admin.courses');


//Student update profile to student database
Route::middleware(['auth'])->group(function () {
    Route::get('/update-profile', [EmployeeController::class, 'show'])->name('profile.show');
    Route::get('/update-profile/edit', [EmployeeController::class, 'edit'])->name('profile.edit');
});

// Route::get('/admin/course/edit/{course_code}', [CourseController::class, 'editCourse'])->name('admin.course.edit');
// Route::post('/admin/course/update/{course_code}', [CourseController::class, 'updateCourse'])->name('admin.course.update');
// Route::delete('admin-course/{course_code}', [CourseController::class, 'deleteCourse'])->name('admin.course.delete');

// // Show the “New Course” form
// Route::get('addcourse', [CourseController::class, 'addCourse'])
//      ->middleware('auth')
//      ->name('admin.course.add');

// // Handle the form POST and store the course
// Route::post('addcourse', [CourseController::class, 'storeCourse'])
//      ->middleware('auth')
//      ->name('admin.course.store');

//      Route::delete('/admin/courses/bulk-delete', [CourseController::class, 'bulkDelete'])->name('admin.courses.bulkDelete');
