@extends('layouts.master')

@section('content')
    <div class="content container-fluid">

        <div class="page-header">
            <h3 class="page-title">Leave & Time Slip Approval</h3>
            <p class="text-muted">Review and approve pending employee requests.</p>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="approvalTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#leave">
                    Leave Requests</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#timeslip">
                    Time Slip Requests</button></li>
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
                                        <td>{{ $leave->created_at->format('d M Y') }}</td>
                                        <td>{{ $leave->employee->full_name }}</td>
                                        <td>{{ ucfirst($leave->leave_type) }}</td>
                                        <td>{{ $leave->start_date->format('d M Y') }} →
                                            {{ $leave->end_date->format('d M Y') }}</td>
                                        <td>{{ $leave->days }} days</td>
                                        <td>{{ $leave->reason }}</td>

                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#leaveModal{{ $leave->id }}" title="View Details">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            <form action="{{ route('leave.updateStatus', $leave->id) }}" method="POST">
                                                @csrf
                                                <button name="action" value="approved" class="btn btn-success btn-sm">
                                                    <i class="bi bi-check-circle"></i> Approve</button>
                                                <button name="action" value="rejected"
                                                    class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-x-circle"></i> Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted p-3">No pending leave requests</td>
                                    </tr>
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
                                @forelse ($pendingTimeSlips as $timeSlip)
                                    <tr>
                                        <td>{{ $timeSlip->created_at->format('d M Y') }}</td>
                                        <td>{{ $timeSlip->employee->full_name }}</td>
                                        <td>{{ $timeSlip->date->format('d M Y') }}</td>
                                        <td>{{ $timeSlip->time_slip_start->format('g:i A') }} -
                                            {{ $timeSlip->time_slip_end->format('g:i A') }}</td>
                                        <td>{{ $timeSlip->time_slip_reason }}</td>

                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#timeSlipModal{{ $timeSlip->id }}" title="View Details">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            <form action="{{ route('timeslip.updateStatus', $timeSlip->id) }}"
                                                method="POST">
                                                @csrf
                                                <button name="action" value="approved" class="btn btn-success btn-sm">
                                                    <i class="bi bi-check-circle"></i> Approve</button>
                                                <button name="action" value="rejected"
                                                    class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-x-circle"></i> Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted p-3">No pending time slips</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($pendingLeaves as $leave)
        <div class="modal fade" id="leaveModal{{ $leave->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Leave Request Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-sm">
                            <tr>
                                <th>Employee</th>
                                <td>{{ $leave->employee->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Leave Type</th>
                                <td>{{ ucfirst($leave->leave_type) }}</td>
                            </tr>
                            <tr>
                                <th>Start Date</th>
                                <td>{{ $leave->start_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>End Date</th>
                                <td>{{ $leave->end_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Duration</th>
                                <td>{{ $leave->days }} days</td>
                            </tr>
                            <tr>
                                <th>Reason</th>
                                <td>{{ $leave->reason }}</td>
                            </tr>
                            <tr>
                                <th>Date Applied</th>
                                <td>{{ $leave->created_at->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <form action="{{ route('leave.updateStatus', $leave->id) }}" method="POST">
                            @csrf
                            <button name="action" value="approved" class="btn btn-success">Approve</button>
                            <button name="action" value="rejected" class="btn btn-danger">Reject</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach


    @foreach ($pendingTimeSlips as $timeSlip)
        <div class="modal fade" id="timeSlipModal{{ $timeSlip->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Time Slip Request Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-sm">
                            <tr>
                                <th>Employee</th>
                                <td>{{ $timeSlip->employee->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Dates</th>
                                <td>{{ $timeSlip->date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Time Slip Start</th>
                                <td>{{ $timeSlip->time_slip_start->format('g:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Time Slip End</th>
                                <td>{{ $timeSlip->time_slip_end->format('g:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Reason</th>
                                <td>{{ $timeSlip->time_slip_reason }}</td>
                            </tr>
                            <tr>
                                <th>Date Applied</th>
                                <td>{{ $timeSlip->created_at->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <form action="{{ route('timeslip.updateStatus', $timeSlip->id) }}" method="POST">
                            @csrf
                            <button name="action" value="approved" class="btn btn-success">Approve</button>
                            <button name="action" value="rejected" class="btn btn-danger">Reject</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
@endsection
