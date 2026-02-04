@extends('layouts.master')

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">Employees</li>
                                    </ol>
                                </nav>
                                <h3 class="page-title"><br>Employees</h3>
                                <p class="text-muted">Manage your team members and their information</p>
                            </div>
                            <button class="btn-new" onclick="window.location='{{ route('admin.employee.create') }}'">
                                Add New Employee
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
                            <select name="department_name" class="form-select">
                                <option value="">All Departments</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept }}"
                                        {{ request('department_name') == $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Employment Status</label>
                            <select name="employment_status_id" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach ($employmentStatuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ request('employment_status_id') == $status->id ? 'selected' : '' }}>
                                        {{ ucfirst($status->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Company Branch</label>
                            <select name="company_branch_id" class="form-select">
                                <option value="">All Branches</option>
                                @foreach ($companyBranches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ request('company_branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Join Date</label>
                            <input type="date" name="date_of_employment" value="{{ request('date_of_employment') }}"
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
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card stat-card active" onclick="window.location='{{ route('admin.employee') }}'"
                    style="cursor:pointer;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-2">Total Employees</h6>
                                <h3 class="mb-0">{{ $totalEmployees }}</h3>
                                <small class="text-success">All employees</small>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-people-fill text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card stat-card"
                    onclick="window.location='{{ route('admin.employee', ['filter' => 'ending']) }}'"
                    style="cursor:pointer;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-2">Ending in/less than 30 days</h6>
                                <h3 class="mb-0">{{ $employmentEnding }}</h3>
                                <small class="text-success">Contract / intern / employment / probation / suspension</small>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-exclamation-triangle text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card stat-card" onclick="window.location='{{ route('admin.employee', ['filter' => 'new']) }}'"
                    style="cursor:pointer;">
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
                <h5 class="card-title mb-0">Employees Details</h5>
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
                                                @if ($employee->user && $employee->user->profile_photo_path)
                                                    <img src="{{ asset('storage/' . $employee->user->profile_photo_url) }}"
                                                        alt="{{ $employee->full_name }}" class="rounded-circle"
                                                        style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('img/default-avatar.png') }}"
                                                        alt="{{ $employee->full_name }}" class="rounded-circle"
                                                        style="width: 40px; height: 40px; object-fit: cover;">
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $employee->full_name }}</strong>
                                                <br>
                                                <small
                                                    class="text-muted">{{ $employee->employment->branch?->name ?? 'Not assigned' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $employee->employee_id }}</td>
                                    <td>
                                        <span
                                            class="badge bg-light text-dark">{{ $employee->employment->department->name ?? 'Not assigned' }}</span>
                                    </td>
                                    <td>{{ $employee->employment->position ?? 'Staff' }}</td>
                                    <td>
                                        <small class="d-block">{{ $employee->phone_number ?? 'N/A' }}</small>
                                        <small class="text-muted">{{ $employee->email }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $status = $employee->employment->status?->name ?? 'active';
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
                                        {{ optional($employee->employment)->date_of_employment ? \Carbon\Carbon::parse($employee->employment->date_of_employment)->format('M d, Y') : 'Not set' }}

                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="window.location='{{ route('profile.editEmployment', $employee->employee_id) }}'">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info"
                                                onclick="window.location='{{ route('profile.show', $employee->employee_id) }}'">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
