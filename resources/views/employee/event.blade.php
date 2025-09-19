@extends('layouts.master')

@section('content')
    <style>
        body {
            background-color: #f4f8fb;
        }

        .datetime-punch {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .datetime {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .btn-leave {
            background-color: #ffc107;
            border: none;
            color: #212529;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            white-space: nowrap;
        }

        .btn-punch:hover {
            background-color: #e0a800;
            color: #212529;
        }

        .card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .card:hover {
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.08);
        }

        .card-body b {
            font-size: 1.5rem;
            font-weight: 600;
            color: #3498db;
        }

        .card-body g {
            font-size: 1.5rem;
            font-weight: 600;
            color: #40d15d;
        }

        .card-body y {
            font-size: 1.5rem;
            font-weight: 600;
            color: #edd641;
        }

        .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        padding: 0 30px;
    }
    .event-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    .event-card:hover {
        transform: translateY(-5px);
    }
    .event-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .event-card-body {
        padding: 20px;
    }
    .event-card-body h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: #333;
    }
    .event-card-body p {
        color: #555;
        font-size: 0.95rem;
        margin-bottom: 15px;
    }
    .event-meta {
        font-size: 0.9rem;
        color: #777;
        margin-bottom: 15px;
    }

        .btn-info {
            background-color: #5dade2;
            border-color: #5dade2;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 500;
        }

        .btn-info:hover {
            background-color: #3498db;
            border-color: #3498db;
        }

        .card-title {
            font-weight: 600;
            color: #2980b9;
            font-size: 1.25rem;
        }

        .card-header p {
            color: #7f8c8d;
            margin-top: 5px;
            font-size: 0.95rem;
        }

        .db-icon img {
            width: 50px;
            opacity: 0.7;
        }

        .db-widgets {
            padding: 10px;
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
                    <div class="page-sub-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="page-title"><br>Events</h3>
                                <p class="text-muted">Manage your events.</p>
                            </div>
                            <button class="btn-leave" onclick="window.location='{{ route('task.create') }}'">
                                New Event
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Total Tasks -->
        <div class="col-12 col-md-2 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    {{-- makes content flexible row-pushes text left, icon right --}}
                    {{-- <div>
                        <div class="card-title">Total Events</div>
                        <b>{{ $stats->$total_events }}</b>
                    </div> --}}
                    <i class="bi bi-list-task me-2 fs-5 text-primary"></i>
                    {{-- fs-smaller>bigger icon --}}
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-12 col-md-12">
            <section class="events-section">
        <div class="section-title">
            <h2>Upcoming Events</h2>
            <p>Join our upcoming programs and experience excellence in event planning.</p>
        </div>

        <div class="events-grid">
            @forelse($events as $event)
            <div class="event-card">
                <img src="{{ asset('images/event-corporate.jpg') }}" alt="Corporate Summit">
                <div class="event-card-body">
                     <h3>{{ $event->event_name }}</h3>
                    <div class="event-meta">
                                📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d F Y') }}
                                •
                                ⏰ {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}
                                •
                                📍 {{ $event->event_location }}
                            </div>
                    <p>{{ Str::limit($event->description, 120) }}</p>

                    @if($event->rsvp_required)
                                <a href="{{ route('events.show',$event->id) }}" class="btn btn-primary">
                                    Register Now
                                </a>
                            @endif
                            
                </div>
            </div>
            @empty
                    <p>No upcoming events at the moment.</p>
                @endforelse
        </div>

    </div>
    </div>
@endsection
