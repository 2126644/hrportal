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
                    <div class="page-sub-header">
                        <div class="d-flex justify-content-between align-items-center">
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
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    {{-- makes content flexible row-pushes text left, icon right --}}
                    <div>
                        <div class="card-title">Total Tasks</div>
                        <b>{{ $totalTasks }}</b>
                    </div>
                    <i class="bi bi-list-task me-2 fs-5 text-primary"></i>  
                    {{-- fs-smaller>bigger icon --}}
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="col-12 col-md-3 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <div class="card-title">To-Do</div>
                        <g>{{ $toDoTasks }}</g>
                    </div>
                    <i class="bi bi-check-circle-fill me-2 fs-5 text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="col-12 col-md-3 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <div class="card-title">In Progress</div>
                        <y>{{ $inProgressTasks }}</y>
                    </div>
                    <i class="bi bi-hourglass-split me-2 fs-5 text-primary"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-2 mb-4">
            <div class="card">
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

        <div class="col-12 col-md-2 mb-4">
            <div class="card">
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
        
        <div class="col-12 col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <div class="card-title">Tasks</div>
                            <b>{{ $completedTasks }}</b>
                        </div>
                        <i class="bi bi-person-badge me-3 fs-5 text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
