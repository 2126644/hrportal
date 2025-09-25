@extends('layouts.master')

@section('content')
    <style>
        body {
            background-color: #f4f8fb;
        }

        .datetime-punch {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .datetime {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .btn-leave {
            background-color: #ffc107;
            border: none;
            color: #212529;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            white-space: nowrap;
        }

        .btn-punch:hover {
            background-color: #e0a800;
            color: #212529;
        }

        .card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .card:hover {
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.08);
        }

        .card-body b {
            font-size: 1.5rem;
            font-weight: 600;
            color: #3498db;
        }

        .card-body g {
            font-size: 1.5rem;
            font-weight: 600;
            color: #40d15d;
        }

        .card-body y {
            font-size: 1.5rem;
            font-weight: 600;
            color: #edd641;
        }

        .btn-info {
            background-color: #5dade2;
            border-color: #5dade2;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 500;
        }

        .btn-info:hover {
            background-color: #3498db;
            border-color: #3498db;
        }

        .card-title {
            font-weight: 600;
            color: #2980b9;
            font-size: 1.25rem;
        }

        .card-header p {
            color: #7f8c8d;
            margin-top: 5px;
            font-size: 0.95rem;
        }

        .db-icon img {
            width: 50px;
            opacity: 0.7;
        }

        .db-widgets {
            padding: 10px;
        }
    </style>

    <div class="content container-fluid">

        <div class="page-header">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header w-100">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <h3 class="page-title"><br>Tasks & Projects</h3>
                                <p class="text-muted">Manage your tasks and track project progress.</p>
                            </div>
                            <button class="btn-leave" onclick="window.location='{{ route('task.create') }}'">
                                New Tasks
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Total Tasks -->
        <div class="col-12 col-md-2 mb-4">
            <div class="card filter-card active" data-status="all" style="cursor: pointer">
                <div class="card-body d-flex justify-content-between">
                    {{-- makes content flexible row-pushes text left, icon right --}}
                    <div>
                        <div class="card-title ">Total Tasks</div>
                        <b>{{ $totalTasks }}</b>
                    </div>
                    <i class="bi bi-list-task me-2 fs-5 text-primary"></i>
                    {{-- fs-smaller>bigger icon --}}
                </div>
            </div>
        </div>

        <!-- To-Do Tasks -->
        <div class="col-12 col-md-3 mb-4">
            <div class="card filter-card" data-status="to-do" style="cursor: pointer">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <div class="card-title">To-Do</div>
                        <g>{{ $toDoTasks }}</g>
                    </div>
                    <i class="bi bi-check-circle-fill me-2 fs-5 text-primary"></i>
                </div>
            </div>
        </div>

        <!-- In-Progress Tasks -->
        <div class="col-12 col-md-3 mb-4">
            <div class="card filter-card" data-status="in-progress" style="cursor: pointer">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <div class="card-title">In Progress</div>
                        <y>{{ $inProgressTasks }}</y>
                    </div>
                    <i class="bi bi-hourglass-split me-2 fs-5 text-primary"></i>
                </div>
            </div>
        </div>

        <!-- In-Review Tasks -->
        <div class="col-12 col-md-2 mb-4">
            <div class="card filter-card active" data-status="in-review" style="cursor: pointer">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <div class="card-title">In Review</div>
                            <b>{{ $inReviewTasks }}</b>
                        </div>
                        <i class="bi bi-search me-2 fs-5 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="col-12 col-md-2 mb-4">
            <div class="card filter-card" data-status="completed" style="cursor: pointer">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <div class="card-title">Completed</div>
                            <b>{{ $completedTasks }}</b>
                        </div>
                        <i class="bi bi-patch-check-fill me-2 fs-5 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card" id="tasksCard">
                <div class="card-body">
                    <h4 class="card-title mb-3 ">Tasks</h4>

                    @forelse ($tasks as $task)
                        <div class="card task-item mb-3 shadow-sm" data-status="{{ $task->status }}">
                            <div class="card-body d-flex justify-content-between align-items-start flex-wrap">
                                {{-- Left side: title & details --}}
                                <div class="me-3 flex-grow-1">
                                    <h5 class="fw-bold mb-2">
                                        {{ $task->title }}
                                    </h5>

                                    <p class="mb-2 text-muted">
                                        {{ $task->description }}
                                    </p>

                                    <div class="mb-1">
                                        <i class="bi bi-person-fill me-1 text-secondary"></i>
                                        <strong>Assigned To:</strong> {{ $task->assigned_to }}
                                    </div>

                                    <div class="mb-1">
                                        <i class="bi bi-person-badge-fill me-1 text-secondary"></i>
                                        <strong>Assigned By:</strong> {{ $task->assigned_by }}
                                    </div>

                                    @if ($task->notes)
                                        <div class="mb-1">
                                            <i class="bi bi-stickies-fill me-1 text-secondary"></i>
                                            <strong>Notes:</strong> {{ $task->notes }}
                                        </div>
                                    @endif

                                    <small class="text-muted d-block mt-2">
                                        <i class="bi bi-clock-history me-1"></i>
                                        Created: {{ $task->created_at->format('d M Y') }} |
                                        Updated: {{ $task->updated_at->format('d M Y') }}
                                    </small>
                                </div>

                                {{-- Right side: status & due date --}}
                                <div class="text-end">
                                    @switch($task->status)
                                        @case('in-progress')
                                            <span class="badge bg-info text-dark mb-2">In-Progress
                                            </span>
                                        @break

                                        @case('in-review')
                                            <span class="badge bg-primary mb-2">In-Review
                                            </span>
                                        @break

                                        @case('completed')
                                            <span class="badge bg-success mb-2">Completed
                                            </span>
                                        @break

                                        @case('to-do')
                                            <span class="badge bg-danger mb-2">To-Do
                                            </span>
                                        @break
                                    @endswitch

                                    <div>
                                        <i class="bi bi-calendar-event me-1 text-secondary"></i>
                                        <strong>Due:</strong> {{ $task->due_date }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                            <p class="text-muted">No tasks found.</p>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>

         <script>
        document.querySelectorAll('.filter-card').forEach(card => {
            card.addEventListener('click', function() {
                // remove active class from all cards 
                document.querySelectorAll('.filter-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                let status = this.dataset.status; // all, to-do, in-progress, in-review, completed
                document.querySelectorAll('#tasksCard .task-item').forEach(item => {
                    if (status === 'all' || item.dataset.status === status) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
    @endsection
