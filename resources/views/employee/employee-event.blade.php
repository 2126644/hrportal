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
                                <h3 class="page-title"><br>Events</h3>
                                <p class="text-muted">Manage your events and view your schedule.</p>
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
                <button class="nav-link active" id="upcoming-event-tab" data-bs-toggle="tab"
                    data-bs-target="#upcoming-event" type="button" role="tab" aria-controls="upcoming-event"
                    aria-selected="true">
                    Calendar
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="past-event-tab" data-bs-toggle="tab" data-bs-target="#past-event"
                    type="button" role="tab" aria-controls="past-event" aria-selected="false">
                    Events
                </button>
            </li>
        </ul>

        <!-- Tabs content -->
        <div class="tab-content border border-top-0 rounded-bottom p-4 bg-white shadow-sm" id="eventTabsContent"
            style="min-height: 500px;">

            <!-- Upcoming Events tab -->
            <div class="tab-pane fade show active" id="upcoming-event" role="tabpanel" aria-labelledby="upcoming-event-tab">
                <div class="row">
                    <!-- Calendar Column -->
                    <div class="col-12 mb-4 calendar-col">
                        <div id="eventCalendar"></div>
                    </div>
                </div>
            </div>

            <!-- Past Events tab -->
            <div class="tab-pane fade" id="past-event" role="tabpanel" aria-labelledby="past-event-tab">
                <div class="events-grid">
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
                                        <span class="badge-rsvp" title="RSVP Required">RSVP</span>
                                    @endif
                                    <span class="event-status {{ $isPast ? 'status-past' : 'status-upcoming' }}">
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
