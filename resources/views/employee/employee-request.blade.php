@extends('layouts.master')

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header w-100">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">Requests</li>
                                    </ol>
                                </nav>
                                <h3 class="page-title"><br>Leave & Time Slip Requests</h3>
                                <p class="text-muted">Manage your leave and time slip requests.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="requestTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="leave-request-tab" data-bs-toggle="tab" data-bs-target="#leave-request"
                type="button" role="tab" aria-controls="leave-request" aria-selected="false">
                Leave Requests
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="timeslip-request-tab" data-bs-toggle="tab" data-bs-target="#timeslip-request"
                type="button" role="tab" aria-controls="timeslip-request" aria-selected="false">
                Time Slip Requests
            </button>
        </li>
    </ul>

    <div class="tab-content border border-top-0 rounded-bottom p-4 bg-white shadow-sm" id="requestTabsContent"
        style="min-height: 500px;">
        <div class="tab-pane fade show active" id="leave-request" role="tabpanel" aria-labelledby="leave-request-tab">

            <div class="card-title">Pending Leave Requests</div>

            <div class="table-responsive">
                <table class="table text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Applied Date</th>
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
                                    <form action="{{ route('leave.destroy.employee', $leave->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to cancel this leave request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="bi bi-x-circle"></i> Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted p-3">No pending leave requests</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Time Slip Tab -->
        <div class="tab-pane fade" id="timeslip-request" role="tabpanel" aria-labelledby="timeslip-request-tab">

            <div class="card-title">Pending Time Slip Requests</div>
            <div class="table-responsive">
                <table class="table text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Applied Date</th>
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
                                <td>{{ $timeSlip->date->format('d M Y') }}</td>
                                <td>{{ $timeSlip->time_slip_start->format('g:i A') }} -
                                    {{ $timeSlip->time_slip_end->format('g:i A') }}</td>
                                <td>{{ $timeSlip->time_slip_reason }}</td>

                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#timeSlipModal{{ $timeSlip->id }}" title="View Details">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <form action="{{ route('timeslip.destroy.employee', $timeSlip->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to cancel this time slip request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="bi bi-x-circle"></i> Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted p-3">No pending time slips</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
@endsection
@section('scripts')
    <script>
        var triggerTabList = [].slice.call(document.querySelectorAll('#requestTabs button'))
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)

            triggerEl.addEventListener('click', function(event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    </script>
@endsection
