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
                                        <li class="breadcrumb-item"><a href="{{ route('task.index.employee') }}">Tasks</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">New Task</li>
                                    </ol>
                                </nav>
                                <h3 class="page-title"><br>New Task</h3>
                                <p class="text-muted">Create new task.</p>
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
                            <label for="task_name" class="form-label">Task Name <span class="text-danger">*</span></label>
                            <input type="text" id="task_name" name="task_name" class="form-control"
                                placeholder="Enter task title" value="{{ old('task_name') }}" required>
                            @error('task_name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="task_desc" class="form-label">Description</label>
                            <textarea id="task_desc" name="task_desc" rows="3" class="form-control" placeholder="Describe the task">{{ old('task_desc') }}</textarea>
                            @error('task_desc')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="project_id" class="form-label">Project</label>
                            <select id="project_id" name="project_id" class="form-control" required>
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Assign To <span class="text-danger">*</span>
                            </label>

                            <div class="border rounded p-3 bg-white">
                                <div class="mb-3">
                                    <label class="form-label text-muted">
                                        Add Department
                                    </label>
                                    <div class="row">
                                        @foreach ($departments as $dept)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input department-checkbox" type="checkbox"
                                                        value="{{ $dept->id }}" id="dept_{{ $dept->id }}"
                                                        name="department_ids[]">
                                                    <label class="form-check-label" for="dept_{{ $dept->id }}">
                                                        {{ $dept->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <hr class="my-3">

                                <div class="mb-3">
                                    <label class="form-label text-muted">
                                        Add Individual Employee
                                    </label>
                                    <select id="employeeSearch" class="form-select">
                                        <option value="">Search employee...</option>
                                        @foreach ($allEmployees as $emp)
                                            <option value="{{ $emp['id'] }}" data-name="{{ $emp['name'] }}"
                                                data-dept="{{ $emp['department'] }}">
                                                {{ $emp['name'] }} ({{ $emp['department'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label text-muted">
                                        Assigned Employees
                                    </label>

                                    <div id="employeeList" class="border rounded p-3 bg-light" style="min-height: 120px;">
                                        <small class="text-muted">No employees selected</small>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="task_status" class="form-label">Task Status <span
                                    class="text-danger">*</span></label>
                            <select id="task_status" name="task_status" class="form-select" required>
                                <option value="" disabled {{ old('task_status') ? '' : 'selected' }}>
                                    Select Status</option>
                                <option value="to-do" {{ old('task_status') === 'to-do' ? 'selected' : '' }}>
                                    To-Do</option>
                                <option value="in-progress" {{ old('task_status') === 'in-progress' ? 'selected' : '' }}>
                                    In-Progress</option>
                                <option value="in-review" {{ old('task_status') === 'in-review' ? 'selected' : '' }}>
                                    In-Review</option>
                                <option value="completed" {{ old('task_status') === 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                            @error('task_status')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
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
                                <a href="{{ route('task.index.admin') }}" class="btn btn-secondary me-2">
                                    Cancel
                                </a>
                            @else
                                <a href="{{ route('task.index.employee') }}" class="btn btn-secondary me-2">
                                    Cancel
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                Create Task
                            </button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let selectedEmployees = new Map(); // id => {id, name, dept}
        let selectedDepartments = new Set();
        let departmentEmployees = new Map(); // deptId => Set(empIds)

        // Script to render selected employees

        function renderEmployees() {
            const container = document.getElementById('employeeList');
            container.innerHTML = '';

            if (selectedEmployees.size === 0) {
                container.innerHTML = '<small class="text-muted">No employees selected</small>';
                return;
            }

            selectedEmployees.forEach(emp => {
                container.innerHTML += `
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div>
                        <strong>${emp.name}</strong>
                        <small class="text-muted">(${emp.department})</small>
                    </div>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="removeEmployee('${emp.id}')">
                        Remove
                    </button>
                    <input type="hidden" name="employee_ids[]" value="${emp.id}">
                </div>
            `;
            });
        }

        function removeEmployee(id) {
            selectedEmployees.delete(id);
            renderEmployees();
        }

        // Script to add individual employees

        document.getElementById('employeeSearch').addEventListener('change', function() {
            const option = this.selectedOptions[0];
            if (!option.value) return;

            const emp = {
                id: option.value,
                name: option.dataset.name,
                department: option.dataset.dept
            };

            selectedEmployees.set(emp.id, emp);
            renderEmployees();
            this.value = '';
        });

        // Script to add employees by department

        document.querySelectorAll('.department-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const deptId = this.value;

                if (this.checked) {
                    fetch(`/departments/${deptId}/employees`)
                        .then(res => res.json())
                        .then(employees => {
                            const empIds = new Set();

                            employees.forEach(emp => {
                                selectedEmployees.set(emp.id, emp);
                                empIds.add(emp.id);
                            });

                            departmentEmployees.set(deptId, empIds);
                            renderEmployees();
                        });
                } else {
                    // remove employees added by this department
                    const empIds = departmentEmployees.get(deptId) || [];

                    empIds.forEach(id => selectedEmployees.delete(id));
                    departmentEmployees.delete(deptId);

                    renderEmployees();
                }
            });
        });
    </script>
@endsection
