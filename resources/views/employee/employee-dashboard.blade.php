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
                    <div class="page-sub-header">
                        <h3 class="page-title"><br>Welcome {{ $employee->full_name }}!</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-header mb-4">
            <div class="dashboard-datetime">
                <div id="currentDateTime">
                    <div class="date mb-1">
                        <i class="bi bi-calendar3"></i>
                        <span id="currentDate"></span>
                    </div>
                    <div class="time">
                        <i class="bi bi-clock"></i>
                        <span id="currentTime"></span>
                    </div>
                </div>
                <button class="btn-new" id="punchInBtn" @if ($todayAttendance && $todayAttendance->time_in) disabled @endif>
                    Punch In
                </button>
                <button class="btn-new" id="punchOutBtn" @if (!$todayAttendance || $todayAttendance->time_out) disabled @endif>
                    Punch Out
                </button>
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
                                            <strong>{{ $task->title }}</strong><br>
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

        // Reusable punch function
        function sendPunch(url, mapId, punchType) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    fetch(url, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                latitude: lat,
                                longitude: lng
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(
                                `You ${punchType} at: ${data.time}, Status: ${data.status ?? data.status_time_in}`
                            );

                            if (mapId) {
                                var map = L.map(mapId).setView([lat, lng], 16);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    maxZoom: 19,
                                }).addTo(map);

                                L.marker([lat, lng]).addTo(map)
                                    .bindPopup(
                                        `${punchType} Location<br>Status: ${data.status ?? data.status_time_in}`
                                    )
                                    .openPopup();
                            }

                            // Disable/enable buttons accordingly
                            if (data.action === 'punchIn') {
                                document.getElementById('punchInBtn').disabled = true;
                                document.getElementById('punchOutBtn').disabled = false;
                            }
                            if (data.action === 'punchOut') {
                                document.getElementById('punchOutBtn').disabled = true;
                            }
                        })
                        .catch(err => console.error(err));
                });
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        }

        // Bind buttons
        document.getElementById('punchInBtn').addEventListener('click', function() {
            sendPunch("{{ route('attendance.punchIn') }}", "map", "punched in");
        });

        document.getElementById('punchOutBtn').addEventListener('click', function() {
            sendPunch("{{ route('attendance.punchOut') }}", "mapOut", "punched out");
        });
    </script>
@endsection
