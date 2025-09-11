@extends('layouts.master')

@section('content')
    <style>
        body {
            background-color: #f4f8fb;
        }

        .datetime-punch {
            display: flex;
            flex-direction: column; /* stack time on top of date */
            justify-content: space-between;
            align-items: center;
            text-align: center; /* center text */
            flex-wrap: wrap;
            gap: 1rem;
        }

        .datetime-time {
            font-size: 2rem; /* larger font for time */
            font-weight: 600;
        }

        .datetime-date {
            font-size: 1rem; /* smaller font for date */
            font-weight: 500;
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
                                <h3 class="page-title"><br>Attendance</h3>
                                <p class="text-muted">Track your daily attendance and working hours.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Total Requests -->
        <div class="col-12 col-md-4 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    {{-- makes content flexible row-pushes text left, icon right --}}
                    
                        <div class="card-title">Check In/Check Out</div>
                        <div class="datetime-punch">
                            <div class="datetime-time" id="currentDateTime"></div>
                            <div class="datetime-date" id="currentDateTime"></div>
                        </div>
                    
                    <i class="bi bi-files me-3 fs-4 text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="col-12 col-md-8 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <div class="card-title">Attendance History</div>
                        <g>11</g>
                    </div>
                    <i class="bi bi-check-circle-fill me-3 fs-4 text-success"></i>
                </div>
            </div>
        </div>

    </div>
    <script>
    function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const dateStr = now.toLocaleDateString(undefined, options);
            const timeStr = now.toLocaleTimeString();
            document.getElementById('currentDateTime').textContent = `${dateStr} - ${timeStr}`;
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
@endsection
