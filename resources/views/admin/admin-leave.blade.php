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
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">Leave</li>
                                    </ol>
                                </nav>
                                <h3 class="page-title"><br>Leave Management</h3>
                                <p class="text-muted">View and manage all employee leave requests.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs navigation -->
    <ul class="nav nav-tabs" id="leaveTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="leave-application-tab" data-bs-toggle="tab" data-bs-target="#leave-application"
                type="button" role="tab" aria-controls="leave-application" aria-selected="false">
                Leave Application
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="leave-report-tab" data-bs-toggle="tab" data-bs-target="#leave-report"
                type="button" role="tab" aria-controls="leave-report" aria-selected="false">
                Leave Report
            </button>
        </li>
    </ul>

    <!-- Tabs content -->
    <div class="tab-content border border-top-0 rounded-bottom p-4 bg-white shadow-sm" id="leaveTabsContent"
        style="min-height: 500px;">

        <!-- Leave Application tab -->
        <div class="tab-pane fade show active" id="leave-application" role="tabpanel" aria-labelledby="leave-application-tab">
            <!-- Filters and Search -->
            <form method="GET" action="{{ route('leave.index.admin') }}">
                <input type="hidden" name="tab" value="leave-application">
                <div class="row g-2 align-items-end">
                    @if (auth()->user()->role_id === 2)
                        <div class="col-12 col-sm-6 col-lg-2">
                            <label class="form-label">Search Employees</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Name or ID...">
                            </div>
                        </div>
                    @endif
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label class="form-label">Leave Type</label>
                        <select name="leave_entitlement_id" class="form-control">
                            <option value="">All Leave Types</option>
                            @foreach ($leaveTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request('leave_entitlement_id') == $type->id ? 'selected' : '' }}>
                                    {{ ucfirst($type->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label class="form-label">Applied Date</label>
                        <input type="date" name="created_at" value="{{ request('created_at') }}" class="form-control">
                    </div>
                    <div class="col-12 col-sm-6 col-lg-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-2"></i>Filter
                        </button>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-1">
                        <a href="{{ route('leave.index.admin') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="row mt-4">
                <!-- Total Requests -->
                <div class="col-12 col-md-3 mb-4">
                    <div class="card filter-card active" data-status="all">
                        <div class="card-body">
                            <i class="bi bi-files"></i>
                            <div class="card-title">Total Requests</div>
                            <span class="stat-number total">{{ $totalRequests }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="col-12 col-md-3 mb-4">
                    <div class="card filter-card" data-status="pending">
                        <div class="card-body">
                            <i class="bi bi-hourglass-split"></i>
                            <div class="card-title">Pending</div>
                            <span class="stat-number pending">{{ $pendingLeaves }}</span>
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="col-12 col-md-3 mb-4">
                    <div class="card filter-card" data-status="approved">
                        <div class="card-body">
                            <i class="bi bi-check-circle-fill"></i>
                            <div class="card-title">Approved</div>
                            <span class="stat-number approved">{{ $approvedLeaves }}</span>
                        </div>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="col-12 col-md-3 mb-4">
                    <div class="card filter-card" data-status="rejected">
                        <div class="card-body">
                            <i class="bi bi-x-circle-fill"></i>
                            <div class="card-title">Rejected</div>
                            <span class="stat-number rejected">{{ $rejectedLeaves }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Leave Requests</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Applied Date</th>
                                        <th>Employee</th>
                                        <th>Leave Type</th>
                                        <th>Dates</th>
                                        <th>Duration</th>
                                        <th>Leave Reason</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="leavesTable">
                                    @foreach ($leaves as $leave)
                                        <tr data-status="{{ $leave->leave_status }}">
                                            <td>{{ $leave->created_at->format('d M Y') }}</td>
                                            <td>{{ $leave->employee->full_name ?? 'N/A' }}</td>
                                            <td>{{ ucfirst($leave->entitlement?->name ?? 'Leave') }}</td>
                                            <td>{{ $leave->start_date->format('d M Y') }} →
                                                {{ $leave->end_date->format('d M Y') }}</td>
                                            <td>{{ $leave->days }} days</td>
                                            <td>{{ $leave->leave_reason }}</td>
                                            <td>
                                                @if ($leave->leave_status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif ($leave->leave_status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Leave actions">
                                                    <!-- View Button: Opens modal (client-side, no form needed) -->
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                        data-bs-target="#leaveModal{{ $leave->id }}"
                                                        title="View Details" type="button">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <!-- Delete Button: Server-side action (wrapped in form) -->
                                                    <form action="{{ route('leave.destroy.admin', $leave->id) }}"
                                                        method="POST" style="display: inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this leave record?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger" type="submit"
                                                            title="Delete Record">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                    <!-- Approve Button: Server-side action (wrapped in form) -->
                                                    @if ($leave->leave_status === 'pending')
                                                        <form action="{{ route('leave.updateStatus', $leave->id) }}"
                                                            method="POST" style="display: inline;"
                                                            onsubmit="return confirm('Are you sure you want to approve this leave request?');">
                                                            @csrf
                                                            <button class="btn btn-sm btn-outline-success" type="submit"
                                                                name="action" value="approved" title="Approve Leave">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- No data message row -->
                                    @if ($leaves->count() == 0)
                                        <tr>
                                            <td colspan="9" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox me-2"></i>No leave record found
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Report tab -->
        <div class="tab-pane fade" id="leave-report" role="tabpanel" aria-labelledby="leave-report-tab">
            <div class="card-title">Leave Report</div>

            <form method="GET" action="{{ route('leave.index.admin') }}" class="mb-3">
                <input type="hidden" name="tab" class="active-tab-input" value="leave-report">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="year" class="form-label">Year</label>
                        <select id="year" name="year" class="form-select" onchange="this.form.submit()">
                            @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="full_name" class="form-label">Employee</label>
                        <select id="full_name" name="full_name" class="form-select" onchange="this.form.submit()">
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->full_name }}"
                                    {{ $emp->full_name == $selectedEmployeeName ? 'selected' : '' }}>
                                    {{ $emp->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>

                    <div class="col-md-2">
                        <a href="{{ route('leave.index.admin', ['year' => $selectedYear, 'full_name' => $selectedEmployeeName]) }}"
                            class="btn btn-success w-100">
                            <i class="bi bi-file-earmark-excel"></i> Export to Excel
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Leave Type</th>
                            <th>Entitlement</th>
                            @for ($m = 1; $m <= 12; $m++)
                                {{-- show each month name (Jan, Feb, … Dec) --}}
                                <th>{{ \Carbon\Carbon::create()->month($m)->shortMonthName }}</th>
                            @endfor
                            <th>Total</th>
                            <th>Leave Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveTypes as $lt)
                            @php
                                $rowTotal = 0;

                                // Normalize type name whether $lt is a model/object or a plain string
                                $typeName = is_object($lt) ? $lt->name ?? (string) $lt : (string) $lt;

                                // get entitlement for this leave type (from controller)
                                $finalEntitlement = $finalEntitlements[$typeName] ?? 0;
                            @endphp
                            <tr>
                                <td class="text-start">{{ ucwords(str_replace('_', ' ', $typeName)) }}</td>
                                {{-- text-start:align to left --}}

                                {{-- show entitlement days (already prorated/full) --}}
                                <td>{{ number_format($finalEntitlement - $rowTotal, 2) }}</td>

                                {{-- loop through all 12 months --}}
                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        // number of leave applications in this month for this type
                                        $count = $reportData[$typeName][$m] ?? 0;

                                        // add to row total (total taken for this leave type)
                                        $rowTotal += $count;
                                    @endphp
                                    <td>{{ $count }}</td>
                                @endfor
                                {{-- total leave taken for this type --}}
                                <td class="fw-bold">{{ $rowTotal }}</td>

                                {{-- leave balance = entitlement - total taken --}}
                                <td class="fw-bold {{ $finalEntitlement - $rowTotal < 0 ? 'text-danger' : '' }}">
                                    {{ number_format($finalEntitlement - $rowTotal, 2) }}
                                </td>
                                {{-- negative balance in red --}}
                            </tr>
                        @endforeach

                        @php
                            // --------------------------
                            // Calculate Totals Row
                            // --------------------------
                            // Monthly totals across ALL leave types

                            // first initialise monthly and grand totals
                            $monthlyTotals = array_fill(1, 12, 0);

                            // grand total across all leave types
                            $grandTotal = 0;
                            foreach ($reportData as $type => $months) {
                                foreach ($months as $m => $cnt) {
                                    $monthlyTotals[$m] += $cnt;
                                    $grandTotal += $cnt;
                                }
                            }
                        @endphp

                        {{-- last row --}}
                        <tr class="fw-bold table-secondary">
                            <td class="text-center" colspan="2">Total</td>
                            {{-- colspan:merge columns --}}

                            {{-- monthly totals across all types --}}
                            @for ($m = 1; $m <= 12; $m++)
                                <td>{{ $monthlyTotals[$m] }}</td>
                            @endfor

                            {{-- grand total (all leave types, all months) --}}
                            <td>{{ $grandTotal }}</td>

                            {{-- no leave balance here because it's per type, not overall --}}
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($leaves as $leave)
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
                                <td>{{ ucfirst($leave->entitlement?->name ?? 'Leave') }}</td>
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
                                <th>Leave Reason</th>
                                <td>{{ $leave->leave_reason }}</td>
                            </tr>
                            <tr>
                                <th>Date Applied</th>
                                <td>{{ $leave->created_at->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="modal-footer">
                        @if ($leave->leave_status === 'pending')
                            <form action="{{ route('leave.updateStatus', $leave->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to approve this leave request?');">
                                @csrf
                                <button class="btn btn-success" type="submit" name="action" value="approved"
                                    title="Approve">Approve</button>
                            </form>
                        @endif

                        <form action="{{ route('leave.destroy.admin', $leave->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this leave record?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" title="Delete Record">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
 
         // --- Leave Application Filters ---
        document.querySelectorAll('.filter-card').forEach(card => {
            card.addEventListener('click', function() {
                // remove active class from all cards 
                document.querySelectorAll('.filter-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                let status = this.dataset.status;
                let visibleCount = 0;

                // Hide all existing static messages first
                document.querySelectorAll('#leavesTable tr:not([data-status])').forEach(staticMsg => {
                    staticMsg.style.display = 'none';
                });

                // Show/hide leave rows based on filter
                document.querySelectorAll('#leavesTable tr[data-status]').forEach(row => {
                    if (status === 'all' || row.dataset.status === status) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Handle the no data message
                const existingStaticMsg = document.querySelector('#leavesTable tr:not([data-status])');
                let noDataRow = document.getElementById('noDataMessage');

                // Remove existing dynamic message if it exists
                if (noDataRow) {
                    noDataRow.remove();
                }

                // If no rows are visible, show appropriate message
                if (visibleCount === 0) {
                    // If there's already a static message (from Blade), show it and update text
                    if (existingStaticMsg) {
                        existingStaticMsg.style.display = '';
                        existingStaticMsg.querySelector('td').innerHTML =
                            `<i class="bi bi-inbox me-2"></i>No ${getStatusText(status).toLowerCase()} leave requests found`;
                    } else {
                        // Create new dynamic message
                        noDataRow = document.createElement('tr');
                        noDataRow.id = 'noDataMessage';
                        noDataRow.innerHTML = `<td colspan="9" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox me-2"></i>No ${getStatusText(status).toLowerCase()} leave requests found
                </td>`;
                        document.getElementById('leavesTable').appendChild(noDataRow);
                    }
                } else {
                    // Hide static message if rows are visible
                    if (existingStaticMsg) {
                        existingStaticMsg.style.display = 'none';
                    }
                }
            });
        });

        function getStatusText(status) {
            const statusMap = {
                'all': 'all',
                'approved': 'approved',
                'pending': 'pending',
                'rejected': 'rejected'
            };
            return statusMap[status] || status;
        }
    </script>
@endsection
