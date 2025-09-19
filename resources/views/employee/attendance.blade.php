@extends('layouts.master')

@section('content')
    <style>
        body {
            background-color: #f4f8fb;
        }

        .datetime-punch {
            display: flex;
            flex-direction: column;
            /* stack time on top of date */
            justify-content: space-between;
            align-items: center;
            text-align: center;
            /* center text */
            flex-wrap: wrap;
            gap: 1rem;
        }

        .datetime-time {
            font-size: 1.5rem;
            /* larger font for time */
            font-weight: 600;
        }

        .datetime-date {
            font-size: 0.75rem;
            /* smaller font for date */
            margin-top: -0.5rem;
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
            color: #2980b9;
            font-size: 1rem;
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
                <div class="card-body">
                    {{-- makes content flexible row-pushes text left, icon right --}}

                    <div class="card-title">Today's Attendance</div>
                    <div class="datetime-punch">
                        <div class="datetime-time" id="currentTime"></div>
                        <div class="datetime-date" id="currentDate"></div>
                    </div>

                    <div class="mt-3">
                        <h5>Time In:
                            <span class="text-primary">
                                {{ $todayAttendance?->time_in ?? '—' }}
                            </span>
                        </h5>
                        <h5>Time Out:
                            <span class="text-primary">
                                {{ $todayAttendance?->time_out ?? '—' }}
                            </span>
                        </h5>
                    </div>

                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="col-12 col-md-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Attendance History</h4>
                    <table class="w-100 text-left text-sm text-gray-600 border-collapse align-middle">
                        <thead>
                            <tr>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Date</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Time-In</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Status Time-In</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Time-Out</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Status Time-Out</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td class="py-3 px-3 border-b border-gray-100">{{ $attendance->date }}</td>
                                    <td class="py-3 px-3 border-b border-gray-100">{{ $attendance->time_in }}</td>
                                    <td class="py-3 px-3 border-b border-gray-100">{{ $attendance->status_time_in }}</td>
                                    <td class="py-3 px-3 border-b border-gray-100">{{ $attendance->time_out }}</td>
                                    <td class="py-3 px-3 border-b border-gray-100">{{ $attendance->status_time_out }}</td>
                                    <td class="py-3 px-3 border-b border-gray-100">
                                        @if ($attendance->status == 'on-site')
                                            <span
                                                class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">On-Site</span>
                                        @elseif ($attendance->status == 'leave')
                                            <span
                                                class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded-full">Leave</span>
                                        @else
                                            <span
                                                class="inline-block bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-1 rounded-full">Off-Site</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <script>
        function updateDateTime() {
            const now = new Date();

            // Format date
            const dateStr = now.toLocaleDateString(undefined, {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Format time (HH:MM:SS)
            const timeStr = now.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            document.getElementById('currentTime').textContent = timeStr;
            document.getElementById('currentDate').textContent = dateStr;
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
@endsection
