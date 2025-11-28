@extends('layouts.master')

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('event.index.employee') }}">Events</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Show Event</li>
                                    </ol>
                                </nav>
                                <h3 class="page-title"><br>Events</h3>
                                <p class="text-muted">Manage your events and view your schedule.</p>
                            </div>
                            <button class="btn-new" onclick="window.location='{{ route('event.edit', $event->id) }}'">
                                Edit Event
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Header -->
        <div class="event-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold mb-3">{{ $event->event_name }}</h1>
                    <div class="d-flex flex-wrap align-items-center mb-3">
                        @php
                            $statusClass = 'status-upcoming';
                            if ($event->event_status === 'ongoing') {
                                $statusClass = 'status-ongoing';
                            } elseif ($event->event_status === 'completed') {
                                $statusClass = 'status-completed';
                            } elseif ($event->event_status === 'cancelled') {
                                $statusClass = 'status-cancelled';
                            }
                        @endphp
                        <span
                            class="status-badge {{ $statusClass }} me-3 text-capitalize">{{ $event->event_status }}</span>
                        <span class="me-3"><i class="bi bi-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</span>
                        <span><i class="bi bi-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</span>
                    </div>
                    <p class="lead mb-0">{{ Str::limit($event->description, 150) }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    @if ($event->price > 0)
                        <div class="fs-4 fw-bold">${{ number_format($event->price, 2) }}</div>
                        <div class="text-white-50">Registration fee</div>
                    @else
                        <div class="fs-4 fw-bold">Free</div>
                        <div class="text-white-50">No cost to attend</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-8 mb-4">
                <div class="card event-details-card mb-4">
                    <div class="event-card-body p-0">
                        @if ($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->event_name }}"
                                class="event-image">
                        @else
                            <div class="event-image bg-light d-flex align-items-center justify-content-center">
                                <div class="text-center text-muted">
                                    <i class="bi bi-image-fill fs-3 mb-3"></i>
                                    <p>No event image available</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-4">
                <div class="card event-details-card mb-4">
                    <div class="card-header">
                        <i class="bi bi-info-circle-fill me-2"></i>About This Event
                    </div>
                    <div class="event-card-body">
                        <p>{{ $event->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-8 mb-4">
                <div class="card event-details-card mb-4">
                    <div class="card-header">
                        <i class="bi bi-list-ul me-2"></i>Event Schedule
                    </div>
                    <div class="event-card-body">
                        @php
                            $eventTime = \Carbon\Carbon::parse($event->event_time);
                            $eventEndTime = $eventTime->copy()->addHours(2); // Assuming 2-hour duration
                        @endphp
                        <div class="event-detail-item">
                            <div class="event-detail-icon">
                                <i class="bi bi-door-open-fill"></i>
                            </div>
                            <div class="event-detail-content">
                                <div class="event-detail-label">Registration & Welcome</div>
                                <div class="event-detail-value">{{ $eventTime->copy()->subMinutes(30)->format('h:i A') }} -
                                    {{ $eventTime->format('h:i A') }}</div>
                            </div>
                        </div>
                        <div class="event-detail-item">
                            <div class="event-detail-icon">
                                <i class="bi bi-mic-fill"></i>
                            </div>
                            <div class="event-detail-content">
                                <div class="event-detail-label">Main Event</div>
                                <div class="event-detail-value">{{ $eventTime->format('h:i A') }} -
                                    {{ $eventEndTime->copy()->subMinutes(30)->format('h:i A') }}</div>
                            </div>
                        </div>
                        <div class="event-detail-item">
                            <div class="event-detail-icon">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="event-detail-content">
                                <div class="event-detail-label">Networking & Closing</div>
                                <div class="event-detail-value">
                                    {{ $eventEndTime->copy()->subMinutes(30)->format('h:i A') }} -
                                    {{ $eventEndTime->format('h:i A') }}</div>
                            </div>
                        </div>
                        <!-- Optional: Add custom schedule items if stored in database -->
                        @if ($event->category === 'workshop')
                            <div class="event-detail-item">
                                <div class="event-detail-icon">
                                    <i class="bi bi-laptop-fill"></i>
                                </div>
                                <div class="event-detail-content">
                                    <div class="event-detail-label">Hands-on Workshop Session</div>
                                    <div class="event-detail-value">
                                        {{ $eventTime->copy()->addMinutes(45)->format('h:i A') }} -
                                        {{ $eventEndTime->copy()->subMinutes(45)->format('h:i A') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-4">
                <div class="card event-details-card mb-4">
                    <div class="card-header">
                        <i class="bi bi-calendar-date me-2"></i>Event Details
                    </div>
                    <div class="event-card-body">
                        <div class="event-detail-item">
                            <div class="event-detail-icon">
                                <i class="bi bi-calendar-date"></i>
                            </div>
                            <div class="event-detail-content">
                                <div class="event-detail-label">Date</div>
                                <div class="event-detail-value">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</div>
                            </div>
                        </div>
                        <div class="event-detail-item">
                            <div class="event-detail-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="event-detail-content">
                                <div class="event-detail-label">Time</div>
                                <div class="event-detail-value">
                                    {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</div>
                            </div>
                        </div>
                        <div class="event-detail-item">
                            <div class="event-detail-icon">
                                <i class="bi bi-map-fill"></i>
                            </div>
                            <div class="event-detail-content">
                                <div class="event-detail-label">Location</div>
                                <div class="event-detail-value">{{ $event->event_location }}</div>
                            </div>
                        </div>
                        <div class="event-detail-item">
                            <div class="event-detail-icon">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="event-detail-content">
                                <div class="event-detail-label">Category</div>
                                <div class="event-detail-value text-capitalize">{{ $event->category }}</div>
                            </div>
                        </div>
                        <div class="event-detail-item">
                            <div class="event-detail-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="event-detail-content">
                                <div class="event-detail-label">Organizer</div>
                                <div class="event-detail-value">{{ $event->organizer }}</div>
                            </div>
                        </div>
                        @if ($event->tags)
                            <div class="event-detail-item">
                                <div class="event-detail-icon">
                                    <i class="bi bi-tags"></i>
                                </div>
                                <div class="event-detail-content">
                                    <div class="event-detail-label">Tags</div>
                                    <div class="event-detail-value">
                                        @php
                                            $tags = is_array($event->tags) ? $event->tags : json_decode($event->tags, true);
                                        @endphp
                                        @foreach ($tags as $tag)
                                            <span class="tag">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="event-detail-item">
                            <div class="event-detail-icon">
                                <i class="bi bi-pencil-fill"></i>
                            </div>
                            <div class="event-detail-content">
                                <div class="event-detail-label">Created By</div>
                                <div class="event-detail-value">Employee #{{ $event->created_by }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-8 mb-4">
                <div class="card event-details-card">
                    <div class="card-header">
                        <i class="bi bi-map-fill me-2"></i>Location
                    </div>
                    <div class="event-card-body p-0">
                        <iframe width="100%" height="250" style="border:0; border-radius: 0 0 10px 10px;"
                            loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps?q={{ urlencode($event->event_location) }}&output=embed">
                        </iframe>
                        <div class="p-2 text-muted small">
                            <i class="bi bi-geo-alt-fill me-1"></i>{{ $event->event_location }}
                        </div>
                    </div>
                </div>
            </div>

            @if ($event->rsvp_required)
                <div class="col-12 col-md-4 mb-4">
                    <!-- Attendance Card -->
                    <div class="card event-details-card mb-4">
                        <div class="card-header">
                            <i class="bi bi-bar-chart-fill me-2"></i>Attendance
                        </div>
                        <div class="event-card-body">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="event-detail-label">Registered</span>
                                <span class="event-detail-value">{{ $event->attendees }} / {{ $event->capacity }}</span>
                            </div>
                            <div class="capacity-meter">
                                @php
                                    $attendancePercentage =
                                        $event->capacity > 0 ? ($event->attendees / $event->capacity) * 100 : 0;
                                @endphp
                                <div class="capacity-fill" style="width: {{ $attendancePercentage }}%"></div>
                            </div>
                            <div class="mt-2">
                                @php
                                    $spotsLeft = $event->capacity - $event->attendees;
                                @endphp
                                <small class="text-muted">{{ $spotsLeft }} spots left</small>
                            </div>

                            <div class="action-buttons">
                                @if ($event->event_status === 'upcoming')
                                    <button class="btn btn-rsvp flex-fill" id="rsvp-button">
                                        <i class="bi bi-check-circle me-2"></i>Register Now
                                    </button>
                                @else
                                    <button class="btn btn-secondary flex-fill" disabled>
                                        <i class="bi bi-slash-circle me-2"></i>Registration Closed
                                    </button>
                                @endif
                                <button class="btn btn-outline-secondary" id="share-button">
                                    <i class="bi bi-share me-2"></i>Share
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle RSVP/Register button click
            const actionButton = document.getElementById('rsvp-button') || document.getElementById(
                'register-button');
            if (actionButton) {
                actionButton.addEventListener('click', function() {
                    // In a real app, you would make an AJAX request here
                    const buttonText = this.id === 'rsvp-button' ? 'RSVP' : 'Registration';
                    alert(`Thank you! Your ${buttonText} has been received.`);
                    this.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i>${buttonText} Confirmed`;
                    this.classList.remove('btn-rsvp');
                    this.classList.add('btn-success');
                    this.disabled = true;
                });
            }

            // Handle share button
            document.getElementById('share-button').addEventListener('click', function() {
                if (navigator.share) {
                    navigator.share({
                            title: '{{ $event->event_name }}',
                            text: 'Check out this event!',
                            url: window.location.href,
                        })
                        .catch(console.error);
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(window.location.href).then(function() {
                        alert('Event link copied to clipboard!');
                    });
                }
            });
        });
    </script>
@endpush
