@extends('layouts.master')

@section('content')
    <style>
        body {
            background-color: #f4f8fb;
        }

        .card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.08);
        }

        .card-body h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .card-body h6 {
            color: #7f8c8d;
            font-size: 0.9rem;
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

        /* .dash-details h4 {
            font-weight: bold;
            color: #34495e;
        }
        .lesson-imgs img {
            width: 30px;
            margin-right: 10px;
        }
        .lesson-activity {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .dash-circle .col-lg-3 {
            margin-top: 20px;
        }

        .dash-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center; /* Center the content horizontally */

        /* Optional: Mobile responsiveness */
        @media (max-width: 768px) {
            .dash-details {
                flex-direction: row;
                /* Stack columns in a row on smaller screens */
            }
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
                        <h3 class="page-title"><br>Welcome {{ $employee->full_name }}!</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-12 col-lg-12 col-xl-8 d-flex">
                <div class="card flex-fill comman-shadow">
                    <div class="card-body">
                        <div class="row">
                            <!-- Calendar Column -->
                            <div class="col-12 col-md-6">
                                <div id="calendar-doctor" class="calendar-container"></div>
                            </div>

                        <!-- Upcoming Events Column -->
                        <div class="col-12 col-md-6">
                        <div class="calendar-info calendar-info1">
                            <div class="up-come-header">
                                <h3>Upcoming Events</h3>
                            </div>

                            @forelse ($upcomingEvents as $subject)
                                <div class="calendar-details">
                                    <p>{{ $subject->course_code }}</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>{{ $subject->course_title }}</h4>
                                            <h5>Time: {{ $event->event_time ?? '-' }}</h5>
                                            <h5>Place: {{ $event->event_place }}</h5>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-warning mb-3">
                                    No upcoming events.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/js/circle-progress.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script src="assets/plugins/simple-calendar/jquery.simple-calendar.js"></script>
    <script src="assets/js/calander.js"></script>

    <link rel="stylesheet" href="assets/plugins/simple-calendar/simple-calendar.css"/>

    </body>
@endsection
