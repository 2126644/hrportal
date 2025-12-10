@extends('layouts.master')

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <h3 class="page-title"><br>Welcome {{ $employee->full_name }}!</h3>
                                <p class="text-muted">Monitor your activities and work overview</p>
                            </div>
                            <div class="datetime-punch text-end">
                                <div class="datetime-time" id="currentTime"></div>
                                <div class="datetime-date" id="currentDate"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="row">

            <!-- Profile Summary -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Profile Summary</h6>
                                <h5>{{ $profile['full_name'] }}</h5>

                                <small class="text-muted d-block">
                                    <i class="bi bi-person-badge me-2 fs-6"></i>Employee ID: {{ $profile['employee_id'] }}
                                </small>

                                <small class="text-muted d-block">
                                    <i class="bi bi-briefcase me-2 fs-6"></i>Position: {{ $profile['position'] }}
                                </small>

                                {{-- <small class="text-muted d-block">
                                    <i class="bi bi-buildings me-2 fs-6"></i>Company Branch:
                                    {{ $profile['company_branch'] }}
                                </small> --}}
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-person-circle text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Summary -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Days Present</h6>
                                <h3>{{ $attendance['days_present'] }}</h3>

                                <small>Last Punch In: {{ $attendance['last_punch_in'] }}</small>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-list-check text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks Summary -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Pending Tasks</h6>
                                <h3>{{ $task['pending_task'] }}</h3>
                                <small class="text-muted">/ {{ $task['total_task'] }} tasks</small>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-clock-history text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Summary -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Upcoming Leaves</h6>
                                <h3>{{ $leave['upcoming_leave'] }}</h3>

                                <small class="text-info">Next Leave: {{ $leave['next_leave_date'] }}</small>
                            </div>
                            <div class="stat-icon">
                                <i class="bi bi-airplane text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Upcoming Tasks Column --}}
            <div class="col-12 col-md-8 mb-4 d-flex">
                <div class="card h-100 w-100">
                    <div class="card-header">
                        <h4 class="card-title">My Tasks</h4>
                    </div>
                    <div class="card-body">
                        {{-- Check if there are no tasks at all --}}
                        @if ($taskRecords->count() === 0)
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-inbox" style="font-size:4rem;"></i>
                                <p>You have no tasks at the moment.</p>
                            </div>
                        @else
                            @php
                                // Define status groups in correct order
                                $statusGroups = [
                                    'to-do' => 'To-Do',
                                    'in-progress' => 'In-Progress',
                                    'in-review' => 'In-Review',
                                    'completed' => 'Completed',
                                ];
                            @endphp

                            @foreach ($statusGroups as $statusKey => $statusLabel)
                                @if (isset($tasksByStatus[$statusKey]) && $tasksByStatus[$statusKey]->count() > 0)
                                    <h6 class="fw-bold mt-3">{{ $statusLabel }}</h6>
                                    <ul class="list-unstyled mb-2">
                                        @foreach ($tasksByStatus[$statusKey] as $taskItem)
                                            <li class="d-flex align-items-start mb-2">
                                                <span class="me-2 mt-1 text-primary">
                                                    <i class="bi bi-circle-fill" style="font-size:0.5rem"></i>
                                                </span>
                                                <div>
                                                    <strong>{{ $taskItem->task_name }}</strong><br>
                                                    <small class="text-muted">
                                                        Due
                                                        {{ optional($taskItem->due_date)->format('d M Y h:i A') ?? '-' }}
                                                    </small>
                                                    @if ($taskItem->due_date && $taskItem->due_date < now() && $taskItem->task_status !== 'completed')
                                                        <span class="badge bg-danger ms-2">Overdue</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Announcements -->
            <div class="col-12 col-md-4 mb-4 d-flex">
                <div class="card h-100 w-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Announcements</h5>
                    </div>
                    <div class="card-body">
                        <!-- Announcements List -->
                        <div class="announcement-list">
                            @forelse($announcements as $announcement)
                                <div class="announcement-item mb-3 p-2 hover-bg" style="border-radius: 8px;">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0">{{ $announcement->title }}</h6>
                                        <span
                                            class="badge 
                                            @if ($announcement->priority == 'high') bg-danger 
                                            @elseif($announcement->priority == 'medium') bg-warning 
                                            @else bg-info @endif">
                                            {{ ucfirst($announcement->priority) }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-1 small">{{ Str::limit($announcement->description, 100) }}</p>
                                    <small class="text-muted">{{ $announcement->created_at->format('M j, g:i A') }}</small>
                                </div>
                            @empty
                                <p class="text-muted small">No recent announcements found.</p>
                            @endforelse
                        </div>

                        <!-- View All Link -->
                        <div class="text-center">
                            <a href="{{ route('announcement.index.admin') }}" class="btn btn-outline-primary btn-sm">
                                View All Announcements <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Upcoming Events Column -->
            <div class="col-12 col-md-8 mb-4 d-flex">
                <div class="card w-100 h-100">
                    <div class="card-header">
                        <h4 class="card-title">Upcoming Events</h4>
                    </div>
                    <div class="card-body">
                        @forelse ($upcomingEvents as $event)
                            <div class="event-item">
                                <div class="event-title">
                                    <strong>{{ $event->event_name }}</strong>
                                </div>

                                @if ($event->rsvp_required)
                                    <div class="text-end">
                                        <span class="event-rsvp" title="RSVP Required">RSVP</span>
                                    </div>
                                @endif

                                <span title="Date">📅 {{ $event->event_date->format('d F Y') }}</span>
                                <span title="Time">⏰ {{ $event->event_time->format('g:i A') }}</span><br>
                                <span title="Location">📍 {{ $event->event_location }}</span><br>
                            </div>
                        @empty
                            <div class="alert alert-warning">
                                No upcoming events.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Calendar Column -->
            <div class="col-12 col-md-4 mb-4 d-flex">
                <div class="card w-100 h-100">
                    <div class="card-header">
                        <h4 class="card-title">Calendar</h4>
                    </div>
                    <div class="card-body">
                        <div id="calendar-doctor" class="calendar-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Update date and time every second
        function updateDateTime() {
            const now = new Date();
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            document.getElementById('currentDate').textContent = now.toLocaleDateString(undefined, dateOptions);
            document.getElementById('currentTime').textContent = now.toLocaleTimeString();
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
@endpush
