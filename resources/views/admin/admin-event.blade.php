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
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Events</li>
                                    </ol>
                                </nav>
                                <h3 class="page-title"><br>Events</h3>
                                <p class="text-muted">Manage all events and schedule.</p>
                            </div>
                            <button class="btn-new" onclick="window.location='{{ route('event.create') }}'">
                                New Event
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <!-- Tabs navigation -->
        <ul class="nav nav-tabs" id="eventTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar"
                    type="button" role="tab" aria-controls="calendar" aria-selected="true">
                    Calendar
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="event-tab" data-bs-toggle="tab" data-bs-target="#event" type="button"
                    role="tab" aria-controls="event" aria-selected="false">
                    Events
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="registration-tab" data-bs-toggle="tab" data-bs-target="#registration"
                    type="button" role="tab" aria-controls="registration" aria-selected="false">
                    Registrations
                </button>
            </li>
        </ul>

        <!-- Tabs content -->
        <div class="tab-content border border-top-0 rounded-bottom p-4 bg-white shadow-sm" id="eventTabsContent"
            style="min-height: 500px;">

            <!-- Calendar tab -->
            <div class="tab-pane fade show active" id="calendar" role="tabpanel" aria-labelledby="calendar-tab">
                <div id="eventCalendar"></div>
            </div>

            <!-- Events tab -->
            <div class="tab-pane fade" id="event" role="tabpanel" aria-labelledby="event-tab">
                <!-- Filters and Search -->
                <form method="GET" action="{{ route('event.index.admin') }}">
                    <input type="hidden" name="tab" id="activeTabInput" value="event">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Search Events</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Name or tags...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}"
                                        {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $category)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="event_status" class="form-control">
                                <option value="">All Status</option>
                                @foreach ($eventStatuses as $status)
                                    <option value="{{ $status }}"
                                        {{ request('event_status') == $status ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date</label>
                            <input type="date" name="event_date" value="{{ request('event_date') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel me-2"></i>Filter
                            </button>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <a href="{{ route('event.index.admin') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
                <div class="events-grid mt-4">
                    @forelse($events as $event)
                        @php
                            $eventDate = \Carbon\Carbon::parse($event->event_date);
                            $eventTime = \Carbon\Carbon::parse($event->event_time);
                            $now = \Carbon\Carbon::now();
                            $isPast = $eventDate->lt($now);
                            $eventImage = $event->image
                                ? asset('storage/' . $event->image) // public/storage/events
                                : asset('img/event-corporate.jpg'); // public/img-default image
                        @endphp

                        <div class="event-card">
                            <img src="{{ $eventImage }}" alt="{{ $event->event_name }}">
                            <div class="event-card-body">
                                <h3 onclick="window.location='{{ route('event.show', $event->id) }}'">
                                    {{ $event->event_name }}
                                </h3>

                                <div class="event-meta">
                                    <span title="Date">📅 {{ $eventDate->format('d F Y') }}</span>
                                    <span title="Time">⏰ {{ $eventTime->format('g:i A') }}</span>
                                    <span title="Location">📍 {{ $event->event_location }}</span>

                                    @if ($event->rsvp_required)
                                        <span class="event-rsvp" title="RSVP Required">RSVP</span>
                                    @endif
                                    <span
                                        class="event-status {{ $isPast ? 'event-status-past' : 'event-status-upcoming' }}">
                                        {{ $isPast ? 'Past' : 'Upcoming' }}
                                    </span>
                                </div>

                                <p class="event-description">{{ Str::limit($event->description, 140) }}</p>

                                @if (!$isPast && $event->rsvp_required)
                                    <a href="{{ route('event.show', $event->id) }}" class="btn-primary">Register Now</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No past events found</h5>
                            <p class="text-muted">There are no past events to display.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('eventCalendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 500,
                themeSystem: 'bootstrap5',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                events: @json($calendarEvents),

                // when user clicks an event
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // stop default behavior

                    if (info.event.url) {
                        window.location.href = info.event.url; // go to event.show page
                    }
                },

                eventDidMount: function(info) {
                    // Tooltip on hover (using Bootstrap tooltip)
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
@endsection
