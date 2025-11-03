@extends('layouts.master')

@section('content')

    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="page-title"><br>Tasks & Projects</h3>
                                <p class="text-muted">Manage your tasks and track project progress.</p>
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
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control"
                                placeholder="Name the task" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="3" class="form-control" placeholder="Describe the task">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="assigned_to" class="form-label">Assigned To <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="assigned_to" name="assigned_to" class="form-control"
                                    placeholder="Enter employee's id" value="{{ old('assigned_to') }}" required>
                                @error('assigned_to')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="assigned_by" class="form-label">Assigned By <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="assigned_by" name="assigned_by" class="form-control"
                                    placeholder="Enter employee's id" value="{{ old('assigned_by') }}" required>
                                @error('assigned_by')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Task Status <span
                                        class="text-danger">*</span></label>
                                <select id="status"name="status" class="form-select" required>
                                    <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select Status
                                    </option>
                                    <option value="to-do" {{ old('status') === 'to-do' ? 'selected' : '' }}>To-Do</option>
                                    <option value="in-progress" {{ old('status') === 'in-progress' ? 'selected' : '' }}>
                                        In-Progress</option>
                                    <option value="in-review" {{ old('status') === 'in-review' ? 'selected' : '' }}>
                                        In-Review</option>
                                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" id="due_date" name="due_date" class="form-control"
                                    value="{{ old('due_date') }}">
                                @error('due_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea id="notes" name="notes" rows="3" class="form-control" placeholder="Additional notes">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                @if ($role_id == 2)
                                    <a href="{{ route('admin.task') }}" class="btn btn-secondary me-2">
                                        Cancel
                                    </a>
                                    @elseif ($role_id == 3)
                                        <a href="{{ route('employee.task') }}" class="btn btn-secondary me-2">
                                            Cancel
                                        </a>
                                    @endif
                                    <button type="submit" class="btn btn-primary">
                                        Create Task
                                    </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
