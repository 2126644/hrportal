@extends('layouts.master')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <h3 class="page-title"><br>Employee Management</h3>
                            <p class="text-muted">Manage your team members and their information</p>
                        </div>
                        <button class="btn-new" onclick="window.location='{{ route('admin.employee') }}'">
                            <i class="bi bi-person-plus me-2"></i>Add Employee
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search Employees</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Search by name, email, or ID...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select class="form-select">
                        <option value="">All Departments</option>
                        <option value="IT">IT</option>
                        <option value="HR">HR</option>
                        <option value="Finance">Finance</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="on_leave">On Leave</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Employees</h6>
                            <h3 class="mb-0">{{ $totalEmployees }}</h3>
                            <small class="text-success">All departments</small>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-people-fill text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Active Today</h6>
                            <h3 class="mb-0">{{ $activeToday }}</h3>
                            <small class="text-success">Present at work</small>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-person-check text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">On Leave</h6>
                            <h3 class="mb-0">{{ $onLeave }}</h3>
                            <small class="text-warning">Away today</small>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-airplane text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">New This Month</h6>
                            <h3 class="mb-0">{{ $newThisMonth }}</h3>
                            <small class="text-info">Recent hires</small>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-person-plus text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Employees</h5>
            <div>
                <button class="btn btn-outline-primary btn-sm me-2">
                    <i class="bi bi-download me-2"></i>Export
                </button>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-upload me-2"></i>Import
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Employee ID</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Join Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        @if($employee->profile_pic)
                                            <img src="{{ asset('storage/' . $employee->profile_pic) }}" 
                                                 alt="{{ $employee->full_name }}" 
                                                 class="rounded-circle"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-person text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $employee->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $employment->company_branch }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $employee->employee_id }}</td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $employee->department ?? 'Not assigned' }}</span>
                            </td>
                            <td>{{ $employee->position ?? 'Staff' }}</td>
                            <td>
                                <small class="d-block">{{ $employee->phone_number ?? 'N/A' }}</small>
                                <small class="text-muted">{{ $employee->email }}</small>
                            </td>
                            <td>
                                @php
                                    $status = $employee->status ?? 'active';
                                    $statusColors = [
                                        'active' => 'success',
                                        'inactive' => 'danger', 
                                        'on_leave' => 'warning'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$status] ?? 'secondary' }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td>
                                {{ $employee->date_joined ? \Carbon\Carbon::parse($employee->date_joined)->format('M j, Y') : 'N/A' }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="window.location='{{ route('admin.dashboard', $employee->id) }}'">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" 
                                            onclick="window.location='{{ route('admin.dashboard', $employee->id) }}'">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} entries
                </div>
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection