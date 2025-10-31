@extends('layouts.master')

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <h3 class="page-title"><br>Tasks & Projects</h3>
                                <p class="text-muted">Manage your tasks and track project progress.</p>
                            </div>
                            <button class="btn-new" onclick="window.location='{{ route('task.create') }}'">
                                New Tasks
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Total Tasks -->
            <div class="col-12 col-md-2 mb-4">
                <div class="card filter-card active" data-status="all">
                    <div class="card-body">
                        <i class="bi bi-list-task"></i>
                        <div class="card-title">Total Tasks</div>
                        <span class="stat-number total">{{ $totalTasks }}</span>
                    </div>
                </div>
            </div>

            <!-- To-Do Tasks -->
            <div class="col-12 col-md-2 mb-4">
                <div class="card filter-card" data-status="to-do">
                    <div class="card-body">
                        <i class="bi bi-circle"></i>
                        <div class="card-title">To-Do</div>
                        <span class="stat-number to-do">{{ $toDoTasks }}</span>
                    </div>
                </div>
            </div>

            <!-- In-Progress Tasks -->
            <div class="col-12 col-md-2 mb-4">
                <div class="card filter-card" data-status="in-progress">
                    <div class="card-body">
                        <i class="bi bi-arrow-repeat"></i>
                        <div class="card-title">In Progress</div>
                        <span class="stat-number in-progress">{{ $inProgressTasks }}</span>
                    </div>
                </div>
            </div>

            <!-- In-Review Tasks -->
            <div class="col-12 col-md-2 mb-4">
                <div class="card filter-card" data-status="in-review">
                    <div class="card-body">
                        <i class="bi bi-eye-fill"></i>
                        <div class="card-title">In Review</div>
                        <span class="stat-number in-review">{{ $inReviewTasks }}</span>
                    </div>
                </div>
            </div>

            <!-- To-Review Tasks -->
            <div class="col-12 col-md-2 mb-4">
                <div class="card filter-card" data-status="to-review">
                    <div class="card-body">
                        <i class="bi bi-bell-fill"></i>
                        <div class="card-title">To-Review</div>
                        <span class="stat-number to-review">{{ $completedTasks }}</span>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks -->
            <div class="col-12 col-md-2 mb-4">
                <div class="card filter-card" data-status="completed">
                    <div class="card-body">
                        <i class="bi bi-check-circle-fill"></i>
                        <div class="card-title">Completed</div>
                        <span class="stat-number completed">{{ $completedTasks }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card" id="tasksCard">
                    <div class="card-body">
                        <h4 class="card-title mb-3 ">Tasks</h4>

                        @forelse ($tasks as $task)
                            <div class="card task-item mb-3" data-status="{{ $task->status }}" data-bs-toggle="modal"
                                data-bs-target="#taskModal{{ $task->id }}" style="cursor:pointer;">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Left side: Task details -->
                                        <div class="col-md-8">
                                            <h5 class="fw-bold mb-2 text-dark">{{ $task->title }}</h5>

                                            <p class="text-muted mb-3">{{ $task->description }}</p>

                                            <div class="task-meta">
                                                <div class="mb-1">
                                                    <i class="bi bi-person-fill me-1 text-secondary"></i>
                                                    <strong>Assigned To:</strong> {{ $task->assigned_to }}
                                                </div>

                                                <div class="mb-1">
                                                    <i class="bi bi-person-badge-fill me-1 text-secondary"></i>
                                                    <strong>Assigned By:</strong> {{ $task->assigned_by }}
                                                </div>

                                                @if ($task->notes)
                                                    <div class="mb-2">
                                                        <i class="bi bi-stickies-fill me-1 text-secondary"></i>
                                                        <strong>Notes:</strong> {{ $task->notes }}
                                                    </div>
                                                @endif
                                            </div>

                                            <small class="text-muted">
                                                <i class="bi bi-clock-history me-1"></i>
                                                Created: {{ $task->created_at->format('d M Y') }} |
                                                Updated: {{ $task->updated_at->format('d M Y') }}
                                            </small>
                                        </div>

                                        <!-- Right side: Status & Due date -->
                                        <div class="col-md-4 text-md-end">
                                            <!-- Status Badge -->
                                            @switch($task->status)
                                                @case('to-do')
                                                    <span class="badge bg-danger mb-3">To-Do</span>
                                                @break

                                                @case('in-progress')
                                                    <span class="badge bg-warning text-dark mb-3">In-Progress</span>
                                                @break

                                                @case('in-review')
                                                    <span class="badge bg-primary mb-3">In-Review</span>
                                                @break

                                                @case('to-review')
                                                    <span class="badge bg-primary mb-3">To-Review</span>
                                                @break

                                                @case('completed')
                                                    <span class="badge bg-success mb-3">Completed</span>
                                                @break
                                            @endswitch

                                            <!-- Due Date -->
                                            <div class="due-date">
                                                <i class="bi bi-calendar-event me-1 text-secondary"></i>
                                                <strong>Due:</strong> {{ $task->due_date }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                                <div class="text-center py-4 text-muted no-tasks-static">
                                    <i class="bi bi-inbox display-6 mb-2"></i>
                                    <p class="mb-0">No tasks found</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        {{-- Update/edit task modal --}}
        @foreach ($tasks as $task)
            <div class="modal fade" id="taskModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('task.update', $task->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-header">
                                <h5 class="modal-title">Task Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Task Title</th>
                                        <td>{{ $task->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{{ $task->description }}</td>
                                    </tr>
                                    <tr>
                                        <th>Assigned to</th>
                                        <td>{{ $task->assigned_to }}</td>
                                    </tr>
                                    <tr>
                                        <th>Assigned by</th>
                                        <td>{{ $task->assigned_by }}</td>
                                    </tr>
                                    <tr>
                                        <th>Notes</th>
                                        <td>{{ $task->notes }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <select id="status" name="status" class="form-select" required>
                                                <option value="" disabled {{ !$task->status ? 'selected' : '' }}>
                                                    Select
                                                    Status</option>

                                                <option value="to-do"
                                                    {{ old('status', $task->status) === 'to-do' ? 'selected' : '' }}>
                                                    To-Do
                                                </option>

                                                <option value="in-progress"
                                                    {{ old('status', $task->status) === 'in-progress' ? 'selected' : '' }}>
                                                    In-Progress
                                                </option>

                                                <option value="in-review"
                                                    {{ old('status', $task->status) === 'in-review' ? 'selected' : '' }}>
                                                    In-Review
                                                </option>

                                                <option value="to-review"
                                                    {{ old('status', $task->status) === 'to-review' ? 'selected' : '' }}>
                                                    To-Review
                                                </option>

                                                <option value="completed"
                                                    {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Due Date</th>
                                        <td>
                                            <input type="date" name="due_date" class="form-control"
                                                value="{{ old('due_date', $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '') }}">
                                            @error('due_date')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>

                                </table>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach


    @push('scripts')
        <script>
            document.querySelectorAll('.filter-card').forEach(card => {
                card.addEventListener('click', function() {
                    // remove active class from all cards 
                    document.querySelectorAll('.filter-card').forEach(c => c.classList.remove('active'));
                    this.classList.add('active');

                    let status = this.dataset.status;
                    let visibleCount = 0;

                    // Hide all existing static messages first
                    document.querySelectorAll('#tasksCard .task-item, #tasksCard .no-tasks-static').forEach(
                        item => {
                            if (item.classList.contains('no-tasks-static')) {
                                item.style.display = 'none';
                            }
                        });

                    // Show/hide task items based on filter
                    document.querySelectorAll('#tasksCard .task-item').forEach(item => {
                        if (status === 'all' || item.dataset.status === status) {
                            item.style.display = '';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Handle the no tasks message
                    const existingStaticMsg = document.querySelector('#tasksCard .no-tasks-static');
                    let noTasksMessage = document.getElementById('noTasksMessage');

                    // Remove existing dynamic message if it exists
                    if (noTasksMessage) {
                        noTasksMessage.remove();
                    }

                    // If no tasks are visible, show appropriate message
                    if (visibleCount === 0) {
                        // If there's already a static message (from Blade), show it and update text
                        if (existingStaticMsg) {
                            existingStaticMsg.style.display = '';
                            existingStaticMsg.querySelector('p').textContent =
                                `No ${getTaskStatusText(status).toLowerCase()} tasks found`;
                        } else {
                            // Create new dynamic message
                            noTasksMessage = document.createElement('div');
                            noTasksMessage.id = 'noTasksMessage';
                            noTasksMessage.className = 'text-center py-4 text-muted no-tasks-dynamic';
                            noTasksMessage.innerHTML = `
                    <i class="bi bi-inbox display-6 mb-2"></i>
                    <p class="mb-0">No ${getTaskStatusText(status).toLowerCase()} tasks found</p>
                `;
                            document.querySelector('#tasksCard .card-body').appendChild(noTasksMessage);
                        }
                    } else {
                        // Hide static message if tasks are visible
                        if (existingStaticMsg) {
                            existingStaticMsg.style.display = 'none';
                        }
                    }
                });
            });

            function getTaskStatusText(status) {
                const statusMap = {
                    'all': 'all',
                    'to-do': 'to-do',
                    'in-progress': 'in progress',
                    'in-review': 'in review',
                    'to-review': 'to review',
                    'completed': 'completed'
                };
                return statusMap[status] || status;
            }
        </script>
    @endpush
