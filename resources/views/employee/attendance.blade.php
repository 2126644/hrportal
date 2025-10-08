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

                    <div class="datetime-punch d-flex align-items-center gap-3 mt-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock-history text-secondary me-2"></i>
                            <div class="datetime-time fw-bold" id="currentTime"></div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="datetime-date" id="currentDate"></div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h5>
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Time In:
                            <span class="text-primary" id="timeInDisplay">
                                {{ $todayAttendance?->time_in ?? '—' }}
                            </span>
                        </h5>
                        <h5>
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Time Out:
                            <span class="text-primary" id="timeOutDisplay">
                                {{ $todayAttendance?->time_out ?? '—' }}
                            </span>
                        </h5>

                    </div>

                    <div id="punchContainer">
                        @if (!$todayAttendance)
                            <button class="btn btn-punch" id="punchInBtn">
                                <i class="bi bi-hand-index-thumb me-1"></i> Punch In
                            </button>
                        @elseif ($todayAttendance && !$todayAttendance->time_out)
                            <button class="btn btn-punch" id="punchOutBtn">
                                <i class="bi bi-hand-index-thumb-fill me-1"></i> Punch Out
                            </button>
                        @else
                            <span class="text-success mt-3">
                                <i class="bi bi-check-circle-fill me-1"></i>You have punched out for today.
                            </span>
                        @endif
                    </div>

                </div>

            </div>
        </div>

        <!-- Attendance History -->
        <div class="col-12 col-md-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Attendance History</h4>

                    <div class="row mb-3">
                        <div class="col-12 d-flex justify-content-end">
                            <a href="{{ route('attendance.export', ['from' => request('from'), 'to' => request('to')]) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel"></i> Export to Excel
                            </a>
                        </div>
                    </div>

                    <table class="w-100 text-left text-sm text-gray-600 border-collapse align-middle">
                        <thead>
                            <tr class="text-start">
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Date</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Time-In</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Status Time-In</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Time-Out</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Status Time-Out</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Status</th>
                                <th class="py-2 px-3 border-b border-gray-200 font-medium">Action</th>

                            </tr>
                        </thead>
                        <tbody id="attendanceHistoryTable">
                            @foreach ($attendances as $attendance)
                                <tr class="text-start">
                                    <td class="py-3 px-3 border-b border-gray-100">{{ $attendance->date }}</td>

                                    <td class="py-3 px-3 border-b border-gray-100">{{ $attendance->time_in }}</td>

                                    {{-- Status Time In with color --}}
                                    <td class="py-3 px-3 border-b border-gray-100">
                                        @if ($attendance->status_time_in === 'On Time')
                                            <span class="badge bg-success">{{ $attendance->status_time_in }}</span>
                                        @elseif ($attendance->status_time_in === 'Late')
                                            <span class="badge bg-danger">{{ $attendance->status_time_in }}</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-3 border-b border-gray-100">{{ $attendance->time_out }}</td>

                                    {{-- Status Time Out with color --}}
                                    <td class="py-3 px-3 border-b border-gray-100">
                                        @if ($attendance->status_time_out === 'On Time')
                                            <span class="badge bg-success">{{ $attendance->status_time_out }}</span>
                                        @elseif ($attendance->status_time_out === 'Early Leave')
                                            <span class="badge bg-danger">{{ $attendance->status_time_out }}</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-3 border-b border-gray-100">{{ $attendance->status }}</td>

                                    <td class="py-3 px-3 border-b border-gray-100">
                                        <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#attendanceModal{{ $attendance->id }}" title="View Details">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                    <div id="attendanceModalContainer">
                        @foreach ($attendances as $attendance)
                            <div class="modal fade" id="attendanceModal{{ $attendance->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-header">
                                                <h5 class="modal-title">Attendance Details ({{ $attendance->date }})</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <th>Date</th>
                                                        <td>{{ $attendance->date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Time In</th>
                                                        <td>{{ $attendance->time_in }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status In</th>
                                                        <td>{{ $attendance->status_time_in }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Time Out</th>
                                                        <td>{{ $attendance->time_out }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status Out</th>
                                                        <td>{{ $attendance->status_time_out }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td>{{ $attendance->status }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Late Reason</th>
                                                        <td>
                                                            @if ($attendance->status_time_in === 'Late')
                                                                <textarea name="late_reason" class="form-control" rows="1">{{ $attendance->late_reason }}</textarea>
                                                            @else
                                                                <span class="text-muted fst-italic">Not Applicable</span>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    <tr class="early-leave-row">
                                                        <th>Early Leave Reason</th>
                                                        <td>
                                                            @if ($attendance->status_time_out === 'Early Leave')
                                                                <textarea name="early_leave_reason" class="form-control" rows="1">{{ $attendance->early_leave_reason }}</textarea>
                                                            @else
                                                                <span class="text-muted fst-italic">Not Applicable</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="container">
                        <div class="d-flex justify-content-center mt-3">
                            {{ $attendances->links() }}
                        </div>
                    </div>
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

        // reusable punch function
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
                // --------------------------
                // Update Punch In/Out Buttons
                // --------------------------
                const container = document.getElementById('punchContainer');
                if (data.action === 'punchIn') {
                    container.innerHTML = `
                        <button class="btn btn-punch" id="punchOutBtn">
                            <i class="bi bi-hand-index-thumb-fill me-1"></i> Punch Out
                        </button>
                    `;
                }
                if (data.action === 'punchOut') {
                    container.innerHTML = `
                        <span class="text-success mt-3">
                            <i class="bi bi-check-circle-fill me-1"></i> You have punched out for today.
                        </span>
                    `;
                }

                // --------------------------
                // Update Today’s Attendance Card
                // --------------------------
                if (data.action === 'punchIn') {
                    document.getElementById('timeInDisplay').textContent = data.time.split(" ")[1];
                }
                if (data.action === 'punchOut') {
                    document.getElementById('timeOutDisplay').textContent = data.time.split(" ")[1];
                }

                // --------------------------
                // Update Attendance History
                // --------------------------
                const historyTable = document.getElementById('attendanceHistoryTable');

                if (data.action === 'punchIn') {
                    const modalId = "attendanceModal" + data.id;

                    // New row
                    const newRow = `
                        <tr class="text-start">
                            <td class="py-3 px-3 border-b border-gray-100">${data.time.split(" ")[0]}</td> 
                            <td class="py-3 px-3 border-b border-gray-100">${data.time.split(" ")[1]}</td>
                            <td class="py-3 px-3 border-b border-gray-100">
                                ${data.status_time_in === 'On Time'
                                    ? '<span class="badge bg-success">On Time</span>'
                                    : '<span class="badge bg-danger">Late</span>'}
                            </td>
                            <td class="py-3 px-3 border-b border-gray-100">—</td>
                            <td class="py-3 px-3 border-b border-gray-100">—</td>
                            <td class="py-3 px-3 border-b border-gray-100">${data.status}</td>
                            <td class="py-3 px-3 border-b border-gray-100">
                                <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#${modalId}" title="View Details">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                    historyTable.insertAdjacentHTML("afterbegin", newRow);

                    // Modal HTML
                    const modalHTML = `
                        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form method="POST" action="/attendance/${data.id}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PUT">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Attendance Details (${data.time.split(" ")[0]})</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <table class="table table-sm">
                                                <tr><th>Date</th><td>${data.time.split(" ")[0]}</td></tr>
                                                <tr><th>Time In</th><td>${data.time.split(" ")[1]}</td></tr>
                                                <tr><th>Status Time In</th><td>${data.status_time_in}</td></tr>
                                                <tr><th>Time Out</th><td>-</td></tr>
                                                <tr><th>Status Time Out</th><td>-</td></tr>
                                                <tr><th>Status</th><td>${data.status}</td></tr>
                                                <tr><th>Late Reason</th>
                                                    <td>
                                                        ${data.status_time_in === 'Late'
                                                            ? `<textarea name="late_reason" class="form-control" rows="1"></textarea>`
                                                            : `<span class="text-muted fst-italic">Not Applicable</span>`}
                                                    </td>
                                                </tr>
                                                <tr><th>Early Leave Reason</th>
                                                    <td><span class="text-muted fst-italic">Not Applicable</span></td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    `;
                    document.getElementById('attendanceModalContainer').insertAdjacentHTML("afterbegin", modalHTML);
                }

                // --------------------------
                // Punch Out → Update existing modal
                // --------------------------
                if (data.action === 'punchOut') {
                    // Find first row (today)
                    const firstRow = historyTable.querySelector("tr");
                    if (firstRow) {
                        const cells = firstRow.querySelectorAll("td");
                        cells[3].textContent = data.time.split(" ")[1]; // Time Out
                        cells[4].innerHTML = data.status_time_out === 'On Time'
                            ? '<span class="badge bg-success">On Time</span>'
                            : '<span class="badge bg-danger">Early Leave</span>';
                    }

                    // Update modal Early Leave Reason
                    const modal = document.querySelector("#attendanceModal" + data.id);
                    if (modal) {
                        const rows = modal.querySelectorAll("tr");
                        const earlyLeaveRow = modal.querySelector(".early-leave-row td"); // finds the right element directly by class name
                        if (data.status_time_out === 'Early Leave') {
                            earlyLeaveRow.innerHTML = `<textarea name="early_leave_reason" class="form-control" rows="1"></textarea>`;
                        } else {
                            earlyLeaveRow.innerHTML = `<span class="text-muted fst-italic">Not Applicable</span>`;
                        }

                        // Update Time Out + Status in modal
                        rows[3].querySelector("td").textContent = data.time.split(" ")[1]; // Time Out
                        rows[4].querySelector("td").textContent = data.status_time_out;
                    }
                }

                // --------------------------
                // Success Alert
                // --------------------------
                alert(`You ${punchType} at: ${data.time}, Status: ${data.status ?? data.status_time_in}`);
                location.reload();
            })
            .catch(err => console.error(err));
        });
    } else {
        alert("Geolocation is not supported by your browser.");
    }
}

        // bind punch in if exists
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'punchInBtn') {
                sendPunch("{{ route('attendance.punchIn') }}", "map", "punched in");
            }
            if (e.target && e.target.id === 'punchOutBtn') {
                sendPunch("{{ route('attendance.punchOut') }}", "mapOut", "punched out");
            }
        });
    </script>
@endsection
