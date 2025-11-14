@extends('layouts.master')

@section('content')
<div class="content container-fluid">

    <div class="page-header">
        <h3 class="page-title">Leave & Time Slip Approval</h3>
        <p class="text-muted">Review and approve pending employee requests.</p>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" id="approvalTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#leave">Leave Requests</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#timeslip">Time Slip Requests</button></li>
    </ul>

    <div class="tab-content">

        <!-- Leave Tab -->
        <div class="tab-pane fade show active" id="leave">
            <div class="card">
                <div class="card-header"><strong>Pending Leave Requests</strong></div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Applied Date</th>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Dates</th>
                                <th>Duration</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($pendingLeaves as $leave)
                                <tr>
                                    <td>{{ $leave->applied_date->format('d M Y') }}</td>
                                    <td>{{ $leave->employee->full_name }}</td>
                                    <td>{{ ucfirst($leave->leave_type) }}</td>
                                    <td>{{ $leave->start_date->format('d M Y') }} → {{ $leave->end_date->format('d M Y') }}</td>
                                    <td>{{ $leave->days }} days</td>
                                    <td>{{ $leave->reason }}</td> 

                                    <td>
                                        <form action="{{ route('leave.updateStatus', $leave->id) }}" method="POST">
                                            @csrf
                                            <button name="action" value="approved" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i> View</button>
                                            <button name="action" value="approved" class="btn btn-success btn-sm"><i class="bi bi-check-circle"></i> Approve</button>
                                            <button name="action" value="rejected" class="btn btn-outline-danger btn-sm"><i class="bi bi-x-circle"></i> Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted p-3">No pending leave requests</td></tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Time Slip Tab -->
        <div class="tab-pane fade" id="timeslip">
            <div class="card">
                <div class="card-header"><strong>Pending Time Slip Requests</strong></div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Applied Date</th>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($pendingTimeSlips as $ts)
                                <tr>
                                    <td>{{ $ts->date->format('d M Y') }}</td>
                                    <td>{{ $ts->employee->full_name }}</td>
                                    <td>{{ $ts->date->format('d M Y') }}</td>
                                    <td>{{ $ts->time_slip_start->format('g:i A') }} - {{ $ts->time_slip_end->format('g:i A') }}</td>
                                    <td>{{ $ts->time_slip_reason }}</td>

                                    <td>
                                        <form action="{{ route('timeslip.updateStatus', $ts->id) }}" method="POST">
                                            @csrf
                                            <button name="action" value="approved" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i> View</button>
                                            <button name="action" value="approved" class="btn btn-success btn-sm"><i class="bi bi-check-circle"></i> Approve</button>
                                            <button name="action" value="rejected" class="btn btn-outline-danger btn-sm"><i class="bi bi-x-circle"></i> Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted p-3">No pending time slips</td></tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
