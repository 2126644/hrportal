@extends('layouts.master')

@section('content')
<style>
    body {
        background-color: #f4f8fb;
    }
    .page-sub-header {
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .page-sub-header h3 {
        margin-bottom: 0.25rem;
        font-weight: 700;
        color: #2980b9;
        font-size: 1.75rem;
    }
    .page-sub-header p {
        color: #6c757d;
        font-size: 1rem;
    }
    .btn-leave {
        background-color: #ffc107;
        border: none;
        color: #212529;
        font-weight: 600;
        padding: 0.5rem 1.8rem;
        border-radius: 8px;
        transition: background-color 0.3s ease;
        white-space: nowrap;
        box-shadow: 0 3px 6px rgba(255, 193, 7, 0.4);
    }
    .btn-leave:hover {
        background-color: #e0a800;
        color: #212529;
        box-shadow: 0 5px 15px rgba(224, 168, 0, 0.6);
    }
    .events-section {
        padding: 0 1.5rem 2rem;
    }
    .section-title h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #34495e;
        margin-bottom: 0.3rem;
    }
    .section-title p {
        color: #7f8c8d;
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
    }
    .event-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .event-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .event-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .event-card:hover img {
        transform: scale(1.05);
    }
    .event-card-body {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .event-card-body h3 {
        font-size: 1.4rem;
        margin-bottom: 0.4rem;
        color: #2c3e50;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    .event-card-body h3:hover {
        color: #2980b9;
        text-decoration: underline;
    }
    .event-meta {
        font-size: 0.9rem;
        color: #7f8c8d;
        margin-bottom: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem 1rem;
        align-items: center;
    }
    .event-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
        user-select: none;
    }
    .event-meta svg {
        width: 16px;
        height: 16px;
        fill: #7f8c8d;
    }
    .event-description {
        color: #555;
        font-size: 1rem;
        flex-grow: 1;
        margin-bottom: 1.25rem;
        line-height: 1.4;
    }
    .btn-primary {
        align-self: flex-start;
        background-color: #2980b9;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        transition: background-color 0.3s ease;
        text-decoration: none;
        color: white;
        white-space: nowrap;
    }
    .btn-primary:hover {
        background-color: #1c5980;
        text-decoration: none;
        color: white;
    }
    .badge-rsvp {
        background-color: #40d15d;
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 12px;
        user-select: none;
        margin-left: auto;
    }
    .event-status {
        font-size: 0.85rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 12px;
        color: white;
        user-select: none;
        margin-left: auto;
    }
    .status-upcoming {
        background-color: #3498db;
    }
    .status-past {
        background-color: #95a5a6;
    }
</style>

<div class="content container-fluid">
    <div class="page-header">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header d-flex justify-content-between align-items-center w-100">
                    <div>
                        <h3 class="page-title">Events</h3>
                        <p class="text-muted mb-0">Manage your events.</p>
                    </div>
                    <button class="btn-leave" onclick="window.location='{{ route('event.create') }}'">New Event</button>
                </div>
            </div>
        </div>
    </div>

    <section class="events-section">
        <div class="section-title">
            <h2>Upcoming Events</h2>
            <p>Join our upcoming programs and experience excellence in event planning.</p>
        </div>

        <div class="events-grid">
            @forelse($events as $event)
                @php
                    $eventDate = \Carbon\Carbon::parse($event->event_date);
                    $eventTime = \Carbon\Carbon::parse($event->event_time);
                    $now = \Carbon\Carbon::now();
                    $isPast = $eventDate->lt($now);
                    $eventImage = $event->image_path ? asset('storage/' . $event->image_path) : asset('images/event-corporate.jpg');
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

                            @if($event->rsvp_required)
                                <span class="badge-rsvp" title="RSVP Required">RSVP</span>
                            @endif
                            <span class="event-status {{ $isPast ? 'status-past' : 'status-upcoming' }}">
                                {{ $isPast ? 'Past' : 'Upcoming' }}
                            </span>
                        </div>

                        <p class="event-description">{{ Str::limit($event->description, 140) }}</p>

                        @if(!$isPast && $event->rsvp_required)
                            <a href="{{ route('event.show', $event->id) }}" class="btn-primary">Register Now</a>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center text-muted fs-5">No upcoming events at the moment.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection