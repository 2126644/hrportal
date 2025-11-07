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
                                        <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item"><a href="{{ route('employee.project') }}">Projects</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">New Project</li>
                                    </ol>
                                </nav>
                                <h3 class="page-title"><br>New Project</h3>
                                <p class="text-muted">Create new project.</p>
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

                    <form action="{{ route('task.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="project_name" class="form-label">Project Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="project_name" name="project_name" class="form-control"
                                placeholder="Name the project" value="{{ old('project_name') }}" required>
                            @error('project_name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="project_desc" class="form-label">Description <span
                                    class="text-danger">*</span></label>
                            <textarea id="project_desc" name="project_desc" rows="3" class="form-control" placeholder="Describe the task">{{ old('project_desc') }}</textarea>
                            @error('project_desc')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="project_status" class="form-label">Project Status <span
                                    class="text-danger">*</span></label>
                            <select id="project_status"name="project_status" class="form-select" required>
                                <option value="" disabled {{ old('project_status') ? '' : 'selected' }}>
                                    Select Status</option>
                                <option value="not-started" {{ old('project_status') === 'not-started' ? 'selected' : '' }}>
                                    Not-Started</option>
                                <option value="in-progress" {{ old('project_status') === 'in-progress' ? 'selected' : '' }}>
                                    In-Progress</option>
                                <option value="on-hold" {{ old('project_status') === 'on-hold' ? 'selected' : '' }}>
                                    On-Hold</option>
                                <option value="completed" {{ old('project_status') === 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                            @error('project_status')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date <span
                                    class="text-danger">*</span></label>
                                <input type="date" id="start_date" name="start_date" class="form-control"
                                    value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date <span
                                    class="text-danger">*</span></label>
                                <input type="date" id="end_date" name="end_date" class="form-control"
                                    value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                @if ($role_id == 2)
                                    <a href="{{ route('project.index.admin') }}" class="btn btn-secondary me-2">
                                        Cancel
                                    </a>
                                @elseif ($role_id == 3)
                                    <a href="{{ route('employee.project') }}" class="btn btn-secondary me-2">
                                        Cancel
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-primary">
                                    Create Project
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
