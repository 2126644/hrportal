@extends('layouts.master')

@section('content')
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
                            <button class="btn-new" onclick="window.location='{{ route('leave.create') }}'">
                                Apply for Leave
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid mt-4">
        <!-- Tabs navigation -->
        <ul class="nav nav-tabs" id="leaveTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar"
                    type="button" role="tab" aria-controls="calendar" aria-selected="true">
                    Calendar
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="leave-application-tab" data-bs-toggle="tab" data-bs-target="#leave-application"
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
            <!-- Calendar tab -->
            <div class="tab-pane fade show active" id="calendar" role="tabpanel" aria-labelledby="calendar-tab">
                <div id="leaveCalendar"></div>
            </div>

            <!-- Leave Application tab -->
            <div class="tab-pane fade" id="leave-application" role="tabpanel" aria-labelledby="leave-application-tab">
                {{-- Insert your leave application form here --}}
                <div class="row">
                    <!-- Total Requests -->
                    <div class="col-12 col-md-3 mb-4">
                        <div class="card filter-card active" data-status="all" style="cursor: pointer">
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
                    <div class="col-12 col-md-3 mb-4">
                        <div class="card filter-card" data-status="approved" style="cursor: pointer">
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
                    <div class="col-12 col-md-3 mb-4">
                        <div class="card filter-card" data-status="pending" style="cursor: pointer">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <div class="card-title">Pending</div>
                                    <y>{{ $pendingLeaves }}</y>
                                </div>
                                <i class="bi bi-hourglass-split me-3 fs-4 text-warning"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Rejected -->
                    <div class="col-12 col-md-3 mb-4">
                        <div class="card filter-card" data-status="rejected" style="cursor: pointer">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <div class="card-title">Rejected</div>
                                    <y>{{ $rejectedLeaves }}</y>
                                </div>
                                <i class="bi bi-hourglass-split me-3 fs-4 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Leave Requests</h4>
                            <table class="w-100 text-left text-sm text-gray-600 border-collapse align-middle">
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
                                <tbody id="leavesTable">
                                    @foreach ($leaves as $leave)
                                        <tr data-status="{{ $leave->status }}">
                                            <td class="py-3 px-3 border-b border-gray-100">{{ $leave->leave_type }}</td>
                                            <td class="py-3 px-3 border-b border-gray-100">{{ $leave->start_date }}</td>
                                            <td class="py-3 px-3 border-b border-gray-100">{{ $leave->end_date }}</td>
                                            <td class="py-3 px-3 border-b border-gray-100">{{ $leave->reason }}</td>
                                            <td class="py-3 px-3 border-b border-gray-100">
                                                <span
                                                    class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">
                                                    @if ($leave->status === 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif ($leave->status === 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="py-3 px-3 border-b border-gray-100">
                                                {{ $leave->applied_date }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Report tab -->
            <div class="tab-pane fade" id="leave-report" role="tabpanel" aria-labelledby="leave-report-tab">
                <h4 class="card-title mb-4">Leave Report</h4>

                <form method="GET" action="{{ route('employee.leave') }}" class="mb-3">
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
                            <select id="full_name" name="full_name" class="form-select"
                                {{ auth()->user()->role == 'employee' ? 'disabled' : '' }} onchange="this.form.submit()">
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->full_name }}"
                                        {{ $emp->full_name == $selectedEmployeeName ? 'selected' : '' }}>
                                        {{ $emp->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>

                    <div class="col-md-2 text-end">
                        <a href="{{ route('leave.export', ['year' => $selectedYear, 'full_name' => $selectedEmployeeName]) }}"
                            class="btn btn-success w-100">
                            <i class="bi bi-file-earmark-excel"></i> Export to Excel
                        </a>
                    </div>
                </div>
            </form>

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
                                $typeName = is_object($lt) ? $lt->leave_type ?? (string) $lt : (string) $lt;

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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('leaveCalendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 500,
                themeSystem: 'bootstrap5',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                events: @json($employeeLeaves),
                eventDidMount: function(info) {
                    // tooltip on hover (using Bootstrap tooltip)
                    var tooltip = new bootstrap.Tooltip(info.el, {
                        title: info.event.title,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                }
            });

            calendar.render();
        });
    </script>

    <script>
        document.querySelectorAll('.filter-card').forEach(card => {
            card.addEventListener('click', function() {
                // remove active class from all cards 
                document.querySelectorAll('.filter-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                let status = this.dataset.status; // all, approved, pending, rejected
                document.querySelectorAll('#leavesTable tr').forEach(row => {
                    if (status === 'all' || row.dataset.status === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
