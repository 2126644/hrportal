@extends('layouts.master')

@section('content')
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title"><br>Welcome {{ $employee->full_name }}!</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-header">
            <div class="datetime-punch">
                <div class="datetime-time" id="currentTime">12:00 PM</div>
                <div class="datetime-date" id="currentDate">Thursday, Oct 9, 2025</div>
            </div>
        </div>

    </div>

    <div class="row">
        <!-- Upcoming Events Column -->
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Upcoming Events</h4>
                    @forelse ($upcomingEvents as $event)
                        <div class="event-item">
                            <div class="event-date-time">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }} -
                                {{ $event->event_time }}</div>
                            <div class="event-title">{{ $event->event_name }}</div>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            No upcoming events.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        {{-- Upcoming Tasks Column --}}
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">My Tasks</h4>
                    @foreach (['to-do' => 'To-Do', 'in-progress' => 'In-Progress', 'in-review' => 'In-Review', 'completed' => 'Completed'] as $key => $label)
                        @if (isset($tasksByStatus[$key]) && $tasksByStatus[$key]->count())
                            <h6 class="fw-bold mt-3">{{ $label }}</h6>
                            <ul class="list-unstyled mb-2">
                                @foreach ($tasksByStatus[$key] as $task)
                                    <li class="d-flex align-items-start mb-2">
                                        <span class="me-2 mt-1 text-primary">
                                            <i class="bi bi-circle-fill" style="font-size:0.5rem"></i>
                                        </span>
                                        <div>
                                            <strong>{{ $task->task_name }}</strong><br>
                                            <small class="text-muted">
                                                Due {{ optional($task->due_date)->format('d M Y h:i A') ?? '-' }}
                                            </small>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Calendar Column -->
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Calendar</h4>
                    <div id="calendar-doctor" class="calendar-container"></div>
                </div>
            </div>
        </div>
    </div>

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
@endsection
