@extends('layouts.master')

@section('content')
    <style>
        body {
            background-color: #f4f8fb;
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
                    {{-- <div>
                        <div class="card-title">Total Requests</div>
                        <b>{{ $totalRequests }}</b>
                    </div> --}}

                    <form action="{{ route('task.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Name the task" required>
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="Describe the task"></textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned To</label>
                            <input type="text" name="assigned_to" class="form-control" placeholder="Enter employee's id" required>
                            @error('assigned_to')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Assigned By</label>
                            <input type="text" name="assigned_by" class="form-control" placeholder="Enter employee's id" required>
                            @error('assigned_by')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Task Status</label>
                            <select name="status" class="form-select" required>
                                <option value="">-- Select Status --</option>
                                <option value="to-do">To-Do</option>
                                <option value="in-progress">In-Progress</option>
                                <option value="in-review">In-Review</option>
                                <option value="completed">Completed</option>
                            </select>
                            @error('status')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                            <div class="col-md-6">
                                <label class="form-label">Due Date</label>
                                <input type="date" name="due_date" class="form-control">
                                @error('due_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        
                        <div class="mt-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Additional notes"></textarea>
                            @error('notes')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                Create Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
