<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>@yield('title', 'Al-Hidayah Group HR Portal')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/ahglogonobg.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

    <!-- Feather Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/feather/feather.css') }}">

    <!-- Flag Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icons/flags/flags.css') }}">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- Simple Calendar (employee dashboard page) -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/simple-calendar/simple-calendar.css') }}">

    <!-- FullCalendar CSS (leave page) -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />

    <!-- DataTables (admin courses page) -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">

    <!-- Bootstrap Icons from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/hrportal.css') }}">

</head>

<body>
    <!-- Top Navigation (minimal) -->
    <nav class="top-navbar">
        {{-- container-fluid spans full width (0 side padding), while .container (used for the content card) has fixed width + padding --}}
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                @auth
                    @if (Auth::user()->role_id == '2')
                        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                            <img src="{{ asset('assets/img/ahglogonobg.png') }}" alt="MySystem Logo"
                                style="height: 45px; width: auto; margin-right: 10px;">
                            Al-Hidayah Group HR Portal
                        </a>
                    @elseif(Auth::user()->role_id == '3')
                        <a class="navbar-brand" href="{{ route('employee.dashboard') }}">
                            <img src="{{ asset('assets/img/ahglogonobg.png') }}" alt="MySystem Logo"
                                style="height: 45px; width: auto; margin-right: 10px;">
                            Al-Hidayah Group HR Portal
                        </a>
                    @endif
                @endauth
            </div>

            <div class="top-nav-items d-flex align-items-center">
                @auth
                    <!-- Punch In / Out Link -->
                    <a href="#" id="punchLink" class="punch-link me-3"
                        data-punch-in-url="{{ route('attendance.punchIn') }}"
                        data-punch-out-url="{{ route('attendance.punchOut') }}"
                        data-punched="{{ isset($isPunchedIn) && $isPunchedIn ? 'true' : 'false' }}">
                        <i id="punchIcon"
                            class="bi {{ isset($isPunchedIn) && $isPunchedIn ? 'bi-person-x' : 'bi-person-check' }}"></i>
                        <span id="punchText">{{ isset($isPunchedIn) && $isPunchedIn ? 'Punch Out' : 'Punch In' }}</span>
                    </a>

                    <a class="logout-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/ahglogonobg.png') }}" alt="Logo"
                    style="height: 30px; margin-right: 8px;">
                <span class="fw-bold text-primary">Menu</span>
            </div>
            <button class="sidebar-close" id="sidebarClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="sidebar-nav">
            @auth
                <!-- Admin Navigation (Role ID: 2) -->
                @if (Auth::user()->role_id == '2')
                    <a class="nav-link {{ request()->is('admin-dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Dashboard</span>
                    </a>
                    <a class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}" href="{{ route('employee.attendance') }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Attendance</span>
                    </a>
                    <a class="nav-link {{ request()->is('leave*') ? 'active' : '' }}" href="{{ route('employee.leave') }}">
                        <i class="bi bi-calendar3"></i>
                        <span>Leave</span>
                    </a>
                    <a class="nav-link {{ request()->is('tasks*') ? 'active' : '' }}" href="{{ route('employee.task') }}">
                        <i class="bi bi-ui-checks"></i>
                        <span>Task</span>
                    </a>
                    <a class="nav-link {{ request()->is('event*') ? 'active' : '' }}" href="{{ route('employee.event') }}">
                        <i class="bi bi-megaphone"></i>
                        <span>Event</span>
                    </a>
                    <a class="nav-link {{ request()->is('update-profile*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Profile</span>
                    </a>

                    <!-- Employee Navigation (Role ID: 3) -->
                @elseif (Auth::user()->role_id == '3')
                    <a class="nav-link {{ request()->is('employee-dashboard') ? 'active' : '' }}" href="{{ route('employee.dashboard') }}">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Dashboard</span>
                    </a>
                    <a class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}" href="{{ route('employee.attendance') }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Attendance</span>
                    </a>
                    <a class="nav-link {{ request()->is('leave*') ? 'active' : '' }}" href="{{ route('employee.leave') }}">
                        <i class="bi bi-calendar3"></i>
                        <span>Leave</span>
                    </a>
                    <a class="nav-link {{ request()->is('tasks*') ? 'active' : '' }}" href="{{ route('employee.task') }}">
                        <i class="bi bi-ui-checks"></i>
                        <span>Task</span>
                    </a>
                    <a class="nav-link {{ request()->is('event*') ? 'active' : '' }}" href="{{ route('employee.event') }}">
                        <i class="bi bi-megaphone"></i>
                        <span>Event</span>
                    </a>
                    <a class="nav-link {{ request()->is('update-profile*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Profile</span>
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid py-3">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-3 mt-4 text-muted">
        <div class="container">
            &copy; {{ date('Y') }} Al-Hidayah Group HR Portal. All Rights Reserved.
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="assets/plugins/simple-calendar/jquery.simple-calendar.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/ical.js/1.4.0/ical.min.js"></script>

    <script src="assets/js/calander.js"></script>

    <!-- Admin list course -->
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script src="{{ asset('assets/js/script.js') }}"></script>

    <!-- Admin dashboard -->
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- C3 & D3 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.16.0/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const navLinks = document.querySelectorAll('.sidebar .nav-link');

            function toggleSidebar() {
                const isShowing = sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
                document.body.style.overflow = isShowing ? 'hidden' : '';
            }

            function hideSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            // Event listeners
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarClose.addEventListener('click', hideSidebar);
            sidebarOverlay.addEventListener('click', hideSidebar);

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    hideSidebar();
                }
            });

            // Auto-hide sidebar on mobile when clicking nav links
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 769) {
                        hideSidebar();
                    }
                });
            });
        });

        // Reusable punch function
        function sendPunch(url, punchType) {
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
                            alert(
                                `You ${punchType} at: ${data.time}, Status: ${data.status ?? data.status_time_in}`
                            );

                            // Toggle link label and icon after punch
                            const link = document.getElementById('punchLink');
                            if (data.action === 'punchIn') {
                                link.innerHTML = '<i class="bi bi-person-x"></i> Punch Out';
                            } else if (data.action === 'punchOut') {
                                link.innerHTML = '<i class="bi bi-person-check"></i> Punch In';
                            }
                        })
                        .catch(err => console.error(err));
                });
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        }

        // Bind punch link
        document.getElementById('punchLink')?.addEventListener('click', function(e) {
            e.preventDefault();

            const isPunchedIn = {{ $isPunchedIn ? 'true' : 'false' }};
            if (isPunchedIn) {
                sendPunch("{{ route('attendance.punchOut') }}", null, "punched out");
            } else {
                sendPunch("{{ route('attendance.punchIn') }}", null, "punched in");
            }
        });
    </script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    @stack('scripts')
</body>

</html>
