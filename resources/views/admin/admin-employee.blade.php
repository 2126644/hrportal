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
                <form method="GET" action="{{ route('admin.employee') }}">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Search Employees</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Name or ID...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Department</label>
                            <select name="department" class="form-select">
                                <option value="">All Departments</option>
                                @php
                                    $departments = \App\Models\Employment::select('department')
                                        ->distinct()
                                        ->pluck('department')
                                        ->filter();
                                @endphp
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept }}"
                                        {{ request('department') == $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Employment Status</label>
                            <select name="employment_status" class="form-select">
                                <option value="">All Statuses</option>
                                @php
                                    $statuses = \App\Models\Employment::select('employment_status')
                                        ->distinct()
                                        ->pluck('employment_status')
                                        ->filter();
                                @endphp
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ request('employment_status') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Company Branch</label>
                            <select name="company_branch" class="form-select">
                                <option value="">All Branches</option>
                                @php
                                    $branches = \App\Models\Employment::select('company_branch')
                                        ->distinct()
                                        ->pluck('company_branch')
                                        ->filter();
                                @endphp
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch }}"
                                        {{ request('company_branch') == $branch ? 'selected' : '' }}>
                                        {{ $branch }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Join Date</label>
                            <input type="date" name="date_joined" value="{{ request('date_joined') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-primary w-100">
                                <i class="bi bi-funnel me-2"></i>Filter
                            </button>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <a href="{{ route('admin.employee') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
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
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                @if ($employee->profile_pic)
                                                    <img src="{{ asset('storage/' . $employee->profile_pic) }}"
                                                        alt="{{ $employee->full_name }}" class="rounded-circle"
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
                                                <small
                                                    class="text-muted">{{ $employee->employment->company_branch ?? 'Not assigned' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $employee->employee_id }}</td>
                                    <td>
                                        <span
                                            class="badge bg-light text-dark">{{ $employee->employment->department ?? 'Not assigned' }}</span>
                                    </td>
                                    <td>{{ $employee->employment->position ?? 'Staff' }}</td>
                                    <td>
                                        <small class="d-block">{{ $employee->phone_number ?? 'N/A' }}</small>
                                        <small class="text-muted">{{ $employee->email }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $status = $employee->employment->employment_status ?? 'active';
                                            $statusColors = [
                                                'active' => 'success',
                                                'terminated' => 'danger',
                                                'on_leave' => 'warning',
                                                'probation' => 'info',
                                                'suspended' => 'secondary',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$status] ?? 'secondary' }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ optional($employee->employment)->date_joined ? \Carbon\Carbon::parse($employee->employment->date_joined)->format('M d, Y') : 'Not set' }}

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
                        Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of
                        {{ $employees->total() }} entries
                    </div>
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
