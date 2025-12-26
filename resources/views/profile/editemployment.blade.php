@extends('layouts.master')

@section('content')
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('admin.employee') }}">Employees</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">Edit Employment</li>
                                    </ol>
                                </nav>
                                <h3 class="page-title"><br>Edit Employment Details</h3>
                                <p class="text-muted">Update employee's employment details below.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body justify-content-between">
                    {{-- makes content flexible row-pushes text left, icon right --}}

                    <form action="{{ route('profile.updateEmployment', $employee->employee_id) }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="col-md-12 mb-3">
                            {{-- mb-3 = margin-bottom 1rem
                            mt-3 = margin-top 1rem
                            g-3 = gap 1rem --}}
                            <label for="employment_id" class="form-label">Employee ID <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="employee_id" name="employee_id" class="form-control"
                                placeholder="Enter your employee ID"
                                value="{{ old('employee_id', $employee->employee_id) }}" required>
                            {{-- Using for="event_name" links the label to the input’s id, so clicking the label focuses the input. --}}
                            @error('employee_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="employment_type" class="form-label">
                                Employment Type <span class="text-danger">*</span>
                            </label>
                            <select id="employment_type" name="employment_type" class="form-select" required>
                                <option value="" disabled
                                    {{ old('employment_type') ? '' : 'selected' }}>
                                    Select type
                                </option>
                                @php
                                    $employment_types = ['full_time', 'part_time', 'contract', 'intern'];
                                @endphp
                                @foreach ($employment_types as $type)
                                    <option value="{{ $type }}"
                                        {{ old('employment_type', $employment?->employment_type) === $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employment_type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="employment_status" class="form-label">Employment Status <span
                                    class="text-danger">*</span></label>
                            <select id="employment_status" name="employment_status" class="form-select" required>
                                <option value="" disabled{{ old('employment_status') ? '' : 'selected' }}>Select
                                    status
                                </option>
                                @php
                                    $employment_statuses = [
                                        'active',
                                        'probation',
                                        'suspended',
                                        'resigned',
                                        'terminated',
                                    ];
                                @endphp
                                @foreach ($employment_statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ old('employment_status', $employment?->employment_status) === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            @error('employment_status')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="company_branch" class="form-label">Company Branch <span
                                    class="text-danger">*</span></label>
                            <select id="company_branch" name="company_branch" class="form-select" required>
                                <option value="" disabled {{ old('company_branch') ? '' : 'selected' }}>Select
                                    branch
                                </option>
                                @php
                                    $company_branches = ['AHG', 'D-8CEFC'];
                                @endphp
                                @foreach ($company_branches as $branch)
                                    <option value="{{ $branch }}"
                                        {{ old('company_branch', $employment?->company_branch) === $branch ? 'selected' : '' }}>
                                        {{ ucfirst($branch) }}</option>
                                @endforeach
                            </select>
                            @error('company_branch')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="report_to" class="form-label">Report To <span class="text-danger">*</span></label>
                            <select id="report_to" name="report_to" class="form-select" required>
                                <option value="" disabled {{ old('report_to') ? '' : 'selected' }}>Select
                                    employee
                                </option>
                                @php
                                    $employees = \App\Models\Employee::where(
                                        'employee_id',
                                        '!=',
                                        $employee->employee_id,
                                    )->get();
                                @endphp
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->employee_id }}"
                                        {{ old('report_to', $employment?->report_to) === $emp->employee_id ? 'selected' : '' }}>
                                        {{ ucfirst($emp->full_name) }}</option>
                                @endforeach
                            </select>
                            @error('report_to')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <select id="department" name="department" class="form-select" required>
                                <option value="" disabled {{ old('department') ? '' : 'selected' }}>Select
                                    department
                                </option>
                                @php
                                    $departments = ['HR', 'IT', 'Finance', 'Marketing', 'Department'];
                                @endphp
                                @foreach ($departments as $branch)
                                    <option value="{{ $branch }}"
                                        {{ old('department', $employment?->department) === $branch ? 'selected' : '' }}>
                                        {{ ucfirst($branch) }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" id="position" name="position" class="form-control"
                                    placeholder="Enter Position" value="{{ old('position', $employment?->position) }}">
                                @error('position')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_joined" class="form-label">Date Joined</label>
                                <input type="date" id="date_joined" name="date_joined" class="form-control"
                                    value="{{ old('date_joined', $employment?->date_joined) }}">
                                @error('date_joined')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="work_start_time" class="form-label">Work Start Time <span
                                        class="text-danger">*</span></label>
                                <input type="time" id="work_start_time" name="work_start_time" class="form-control"
                                    value="{{ old('work_start_time', $employment?->work_start_time?->format('H:i')) }}"
                                    required>
                                @error('work_start_time')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="work_end_time" class="form-label">Work End Time <span
                                        class="text-danger">*</span></label>
                                <input type="time" id="work_end_time" name="work_end_time" class="form-control"
                                    value="{{ old('work_end_time', $employment?->work_end_time?->format('H:i')) }}"
                                    required>
                                @error('work_end_time')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="probation_start" class="form-label">Probation Start</label>
                                <input type="date" id="probation_start" name="probation_start" class="form-control"
                                    value="{{ old('probation_start', $employment?->probation_start) }}">
                                @error('probation_start')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="probation_end" class="form-label">Probation End</label>
                                <input type="date" id="probation_end" name="probation_end" class="form-control"
                                    value="{{ old('probation_end', $employment?->probation_end) }}">
                                @error('probation_end')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="suspended_start" class="form-label">Suspended Start</label>
                                <input type="date" id="suspended_start" name="suspended_start" class="form-control"
                                    value="{{ old('suspended_start', $employment?->suspended_start) }}">
                                @error('suspended_start')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="suspended_end" class="form-label">Suspended End</label>
                                <input type="date" id="suspended_end" name="suspended_end" class="form-control"
                                    value="{{ old('suspended_end', $employment?->suspended_end) }}">
                                @error('suspended_end')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="resigned_date" class="form-label">Resigned Date</label>
                                <input type="date" id="resigned_date" name="resigned_date" class="form-control"
                                    value="{{ old('resigned_date', $employment?->resigned_date) }}">
                                @error('resigned_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="termination_date" class="form-label">Termination Date</label>
                                <input type="date" id="termination_date" name="termination_date" class="form-control"
                                    value="{{ old('termination_date', $employment?->termination_date) }}">
                                @error('termination_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('profile.show', $employee->employee_id) }}" class="btn btn-secondary me-2">
                                Cancel
                            </a>
                            {{-- later add if/else for employee/admin --}}
                            <button type="submit" class="btn btn-primary">
                                Update Profile
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('employees.approvers.store', $employee) }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Level 1 Approver</label>
                                <select name="approvers[0][id]" class="form-control">
                                    @foreach ($approverCandidates as $approver)
                                        <option value="{{ $approver->employee_id }}">
                                            {{ $approver->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="approvers[0][level]" value="1">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Level 2 Approver</label>
                                <select name="approvers[1][id]" class="form-control">
                                    <option value="">— Optional —</option>
                                    @foreach ($approverCandidates as $approver)
                                        <option value="{{ $approver->employee_id }}">
                                            {{ $approver->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="approvers[1][level]" value="2">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('profile.show', $employee->employee_id) }}" class="btn btn-secondary me-2">
                                Cancel
                            </a>
                            {{-- later add if/else for employee/admin --}}
                            <button type="submit" class="btn btn-primary">
                                Update Approvers
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
