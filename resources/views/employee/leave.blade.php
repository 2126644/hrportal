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
                                <h3 class="page-title"><br>Leave Management</h3>
                                <p class="text-muted">Manage your leave requests and view your balance.</p>
                            </div>
                            <button class="btn-leave" onclick="window.location='{{ route('leave.create') }}'">
                                Apply for Leave
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Total Requests -->
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    {{-- makes content flexible row-pushes text left, icon right --}}
                    <div>
                        <div class="card-title">Total Requests</div>
                        <b>{{ $totalRequests }}</b>
                    </div>
                    <i class="bi bi-files me-3 fs-4 text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <div class="card-title">Approved</div>
                        <g>{{ $approvedLeaves }}</g>
                    </div>
                    <i class="bi bi-check-circle-fill me-3 fs-4 text-success"></i>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <div class="card-title">Pending</div>
                        <y>{{ $pendingLeaves }}</y>
                    </div>
                    <i class="bi bi-hourglass-split me-3 fs-4 text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <div class="card-title">Annual Leave Balance</div>
                            <b>{{ $leaveBalance }}</b>
                        </div>
                        <i class="bi bi-calendar-check me-3 fs-4 text-info"></i>
                    </div>

                    @php
                        $total = 14;
                        $usedDays = 12;
                        $usedDays = $total - $leaveBalance;
                        $percentage = ($usedDays / $total) * 100;
                    @endphp

                    <!-- Progress Bar -->
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%;"
                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>

                    <small class="text-muted d-block mt-2">
                        {{ $usedDays }} used of {{ $total }} days
                    </small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <div class="card-title">Sick Leave Balance</div>
                            <b>{{ $leaveBalance }}</b>
                        </div>
                        <i class="bi bi-emoji-dizzy me-3 fs-4 text-danger"></i>
                    </div>

                    @php
                        $total = 14;
                        $usedDays = 12;
                        $usedDays = $total - $leaveBalance;
                        $percentage = ($usedDays / $total) * 100;
                    @endphp

                    <!-- Progress Bar -->
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%;"
                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>

                    <small class="text-muted d-block mt-2">
                        {{ $usedDays }} used of {{ $total }} days
                    </small>
                </div>
            </div>
        </div>


        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <div class="card-title">Personal Leave Balance</div>
                            <b>{{ $leaveBalance }}</b>
                        </div>
                        <i class="bi bi-person-badge me-3 fs-4 text-secondary"></i>
                    </div>

                    @php
                        $total = 14;
                        $usedDays = 12;
                        $usedDays = $total - $leaveBalance;
                        $percentage = ($usedDays / $total) * 100;
                    @endphp

                    <!-- Progress Bar -->
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%;"
                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>

                    <small class="text-muted d-block mt-2">
                        {{ $usedDays }} used of {{ $total }} days
                    </small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Leave Requests</h4>
                    <table class="w-100 text-left text-sm text-gray-600 border-collapse align-middle">
                        {{-- full width, text left aligned, small text, gray text, border collapse, vertical align middle --}}
                        <thead>
                            <tr>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Type</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Start Date</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">End Date</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Reason</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Status</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Applied</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaves as $leave)
                                <tr>
                                    <td class="py-3 px-3 border-b border-gray-100">{{ $leave->leave_type }}</td>
                                    <td class="py-3 px-3 border-b border-gray-100">{{ $leave->start_date }}</td>
                                    <td class="py-3 px-3 border-b border-gray-100">{{ $leave->end_date }}</td>
                                    <td class="py-3 px-3 border-b border-gray-100">{{ $leave->reason }}</td>
                                    <td class="py-3 px-3 border-b border-gray-100">
                                        <span
                                            class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">
                                            @if ($leave->status === 'Approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif ($leave->status === 'Rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 border-b border-gray-100">
                                        {{ $leave->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
