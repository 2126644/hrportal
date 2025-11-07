@extends('layouts.master')

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <h3 class="page-title"><br>Admin Dashboard</h3>
                                <p class="text-muted">Monitor team activities and system overview</p>
                            </div>
                            <div class="datetime-punch text-end">
                                <div class="datetime-time" id="currentTime"></div>
                                <div class="datetime-date" id="currentDate"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="row mb-4">
            <!-- Total Employees -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-2">Total Employees</h6>
                                <h3 class="mb-0">{{ $totalEmployees }}</h3>
                                <small class="text-success">Active employees</small>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-people-fill text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Present Today -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-2">Present Today</h6>
                                <h3 class="mb-0">{{ $presentToday }}</h3>
                                <small class="text-muted">/ {{ $totalEmployees }} employees</small>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-person-check text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Leave -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-2">Pending Leave</h6>
                                <h3 class="mb-0">{{ $pendingLeaves }}</h3>
                                <small class="text-warning">Requires approval</small>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-clock-history text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Tasks -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-2">Active Tasks</h6>
                                <h3 class="mb-0">{{ $activeTasks }}</h3>
                                <small class="text-info">In progress</small>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-list-task text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="row">
            <div class="col-12 col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">System Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="system-status">
                            <div class="status-item d-flex justify-content-between align-items-center mb-2">
                                <span>Database</span>
                                <span class="badge bg-success">Online</span>
                            </div>
                            <div class="status-item d-flex justify-content-between align-items-center mb-2">
                                <span>Active Users</span>
                                <span class="text-muted">{{ $totalEmployees }}</span>
                            </div>
                            <div class="status-item d-flex justify-content-between align-items-center">
                                <span>Last Update</span>
                                <span class="text-muted">{{ now()->format('M j, g:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-12 col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.employee') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-people me-2"></i>Manage Employees
                            </a>
                            <a href="{{ route('admin.attendance') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-clock-history me-2"></i>View Attendance
                            </a>
                            <a href="{{ route('leave.index.admin') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-calendar-check me-2"></i>Leave Requests
                            </a>
                            <a href="{{ route('task.index.admin') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-list-task me-2"></i>Manage Tasks
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Today's Attendance - Fixed -->
            <div class="col-12 col-md-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Today's Attendance</h5>
                        <div class="text-end">
                            <div class="text-muted small">{{ \Carbon\Carbon::today()->format('l') }}</div>
                            <div class="text-primary fw-semibold">{{ \Carbon\Carbon::today()->format('F j, Y') }}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Attendance Stats with Clickable Cards -->
                        <div class="row text-center mb-4">
                            <div class="col-4">
                                <div class="attendance-stat-card present-card" data-filter="present">
                                    <h4 class="text-success">{{ $presentToday }}</h4>
                                    <small class="text-muted">Present</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="attendance-stat-card absent-card" data-filter="absent">
                                    <h4 class="text-danger">{{ $absentToday }}</h4>
                                    <small class="text-muted">Absent</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="attendance-stat-card late-card" data-filter="late">
                                    @php
                                        $lateToday = \App\Models\Attendance::whereDate('date', \Carbon\Carbon::today())

                                            ->where('status_time_in', 'Late')
                                            ->count();
                                    @endphp
                                    <h4 class="text-warning">{{ $lateToday }}</h4>
                                    <small class="text-muted">Late</small>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Details Section -->
                        <div class="attendance-details">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0" id="attendanceTitle">All Employees</h6>
                                <button class="btn btn-sm btn-outline-secondary" id="showAllEmployees">
                                    <i class="bi bi-arrow-counterclockwise"></i> Show All
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th>Punch In</th>
                                            <th>Punch Out</th>
                                        </tr>
                                    </thead>
                                    <tbody id="attendanceTable">
                                        <!-- Data will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <div id="noAttendanceData" class="text-center text-muted py-4 d-none">
                                <i class="bi bi-people display-4"></i>
                                <p class="mt-2">No employees found</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="col-12 col-md-4 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline" style="max-height: 300px; overflow-y: auto;">
                            @forelse($recentActivities as $activity)
                                <div class="activity-item d-flex mb-3">
                                    <div class="activity-icon me-3">
                                        <i class="bi bi-{{ $activity['icon'] }} text-primary"></i>
                                    </div>
                                    <div class="activity-content flex-grow-1">
                                        <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                        <p class="text-muted mb-1">{{ $activity['description'] }}</p>
                                        <small class="text-muted">{{ $activity['time'] }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-inbox"></i>
                                    <p class="mt-2">No recent activities</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sample data - replace with real data from controller
            const employees = @json($allEmployees);
            const attendanceData = @json($todayAttendance);

            const attendanceTable = document.getElementById('attendanceTable');
            const noAttendanceData = document.getElementById('noAttendanceData');
            const attendanceTitle = document.getElementById('attendanceTitle');

            // function to render employee table
            function renderEmployees(filter = 'all') {
                let filteredEmployees = employees;

                if (filter === 'present') {
                    filteredEmployees = employees.filter(emp =>
                        attendanceData.some(att => att.employee_id === emp.employee_id && att.time_in)
                    );
                    attendanceTitle.textContent = 'Present Employees';
                } else if (filter === 'absent') {
                    filteredEmployees = employees.filter(emp =>
                        !attendanceData.some(att => att.employee_id === emp.employee_id && att.time_in)
                    );
                    attendanceTitle.textContent = 'Absent Employees';
                } else if (filter === 'late') {
                    filteredEmployees = employees.filter(emp => {
                        const empAttendance = attendanceData.find(att => att.employee_id === emp
                            .employee_id);
                        return empAttendance && empAttendance.status_time_in === 'Late';
                    });
                    attendanceTitle.textContent = 'Late Employees';
                } else {
                    attendanceTitle.textContent = 'All Employees';
                }

                // Clear table
                attendanceTable.innerHTML = '';

                if (filteredEmployees.length === 0) {
                    noAttendanceData.classList.remove('d-none');
                    return;
                }

                noAttendanceData.classList.add('d-none');

                // Populate table
                filteredEmployees.forEach(employee => {
                    const attendance = attendanceData.find(att => att.employee_id === employee.employee_id);

                    const row = document.createElement('tr');
                    row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-person text-muted"></i>
                        </div>
                        <div>
                            <strong>${employee.full_name}</strong>
                            <br>
                            <small class="text-muted">${employee.position}</small>
                        </div>
                    </div>
                </td>
                <td>${employee.department || '-'}</td>
                <td>
                    ${attendance ? 
                        `<span class="badge bg-success">Present</span>` : 
                        `<span class="badge bg-danger">Absent</span>`
                    }
                    ${attendance && attendance.status_time_in === 'Late' ? 
                        `<br><small class="text-warning">Late</small>` : ''
                    }
                </td>
                <td>${attendance ? attendance.time_in : '-'}</td>
                <td>${attendance ? (attendance.time_out || '-') : '-'}</td>
            `;
                    attendanceTable.appendChild(row);
                });
            }

            // Click handlers for attendance cards
            document.querySelectorAll('.attendance-stat-card').forEach(card => {
                card.style.cursor = 'pointer';
                card.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    renderEmployees(filter);

                    // Update active state
                    document.querySelectorAll('.attendance-stat-card').forEach(c => {
                        c.style.opacity = '1';
                    });
                    this.style.opacity = '0.8';
                });
            });

            // Show all employees
            document.getElementById('showAllEmployees').addEventListener('click', function() {
                renderEmployees('all');
                document.querySelectorAll('.attendance-stat-card').forEach(c => {
                    c.style.opacity = '1';
                });
            });

            // Initial render
            renderEmployees('all');
        });

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            document.getElementById('currentDate').textContent = now.toLocaleDateString(undefined, dateOptions);
            document.getElementById('currentTime').textContent = now.toLocaleTimeString();
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
@endsection
