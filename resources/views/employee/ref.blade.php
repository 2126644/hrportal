@extends('layouts.master')

@section('styles')
    <style>
        body {
            background: #f8f9fa;
        }

        .event-header {
            background: #0d6efd;
            color: white;
            padding: 2rem;
            border-radius: 0 0 15px 15px;
            margin-bottom: 2rem;
            text: white;
        }

        .event-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
        }

        .location-icon {
            font-size: 4rem;
            color: #0d6efd;
        }

        .agenda-item {
            margin-bottom: 1rem;
        }

        .rsvp-btn {
            background-color: #0d6efd;
            color: white;
        }

        .rsvp-btn:hover {
            background-color: #0b5ed7;
            color: white;
        }
    </style>
    </head>
@endsection

@section('content')
    @yield('styles')

    <header class="event-header text-center">
        <h1>{{ $event->event_name }}</h1>
        <p class="lead">{{ $event->description }}</p>
        <small>
            <i class="bi bi-calendar-event"></i>
            {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
            |
            <i class="bi bi-clock"></i>
            {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
            |
            <i class="bi bi-geo-alt"></i>
            {{ $event->event_location }}
        </small>
    </header>

    <main class="container">
        <div class="row g-4">
            <!-- Left column: Event details -->
            <section class="col-lg-8">
                <h3>Event Overview</h3>
                <p>{{ $event->description }}</p>

                <h4>Agenda</h4>
                <ul class="list-group mb-4">
                    <li class="list-group-item agenda-item">
                        <strong>09:00 AM - 10:00 AM:</strong> Welcome Breakfast & Registration
                    </li>
                    <li class="list-group-item agenda-item">
                        <strong>10:00 AM - 12:00 PM:</strong> Keynote Speech by CEO & Panel Discussion
                    </li>
                    <li class="list-group-item agenda-item">
                        <strong>12:00 PM - 01:00 PM:</strong> Lunch Break
                    </li>
                    <li class="list-group-item agenda-item">
                        <strong>01:00 PM - 03:00 PM:</strong> Workshops & Team Challenges
                    </li>
                    <li class="list-group-item agenda-item">
                        <strong>03:00 PM - 04:30 PM:</strong> Awards & Recognitions
                    </li>
                    <li class="list-group-item agenda-item">
                        <strong>04:30 PM - 05:00 PM:</strong> Closing Remarks & Networking
                    </li>
                </ul>

                <h4>Documents & Resources</h4>
                <ul>
                    <li><a href="#" target="_blank">Event Brochure (PDF)</a></li>
                    <li><a href="#" target="_blank">Workshop Materials</a></li>
                    <li><a href="#" target="_blank">Health & Safety Guidelines</a></li>
                </ul>
            </section>

            <!-- Right column: Sidebar with organizer and location -->
            <aside class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Organizer</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="https://i.ibb.co/3c6KfYv/organizer.jpg" alt="Organizer" class="rounded-circle mb-3"
                            width="120" />
                        <h6>HR Department</h6>
                        <p>Contact: hr@alhidayahgroup.com</p>
                        <p>Phone: +971 4 123 4567</p>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Event Location</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="location-icon mb-3">&#x1F4CD;</div>
                        <p>Al-Hidayah Group Headquarters</p>
                        <p>123 Business Bay, Dubai, UAE</p>
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3609.1234567890123!2d55.27078231510908!3d25.204849983583536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f6f3f156789ab%3A0x123abc456def7890!2sBusiness%20Bay%2C%20Dubai%2C%20UAE!5e0!3m2!1sen!2sus!4v1680000000000!5m2!1sen!2sus"
                            width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button class="btn rsvp-btn w-100">RSVP Now</button>
                    <small class="d-block mt-2 text-muted">Please confirm your attendance by June 1, 2024.</small>
                </div>
            </aside>
        </div>
    </main>
@endsection

<script>
    // Dark mode toggle logic
    const toggleBtn = document.getElementById('darkModeToggle');
    toggleBtn.addEventListener('click', () => {
        document.body.classList.toggle('bg-dark');
        document.body.classList.toggle('text-white');
        if (document.body.classList.contains('bg-dark')) {
            toggleBtn.textContent = '☀️';
        } else {
            toggleBtn.textContent = '🌙';
        }
    });
</script>
