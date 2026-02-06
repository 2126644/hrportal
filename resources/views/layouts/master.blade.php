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
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

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
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/hrportal.css') }}">

</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Top Navigation -->
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
                    @else
                        <a class="navbar-brand" href="{{ route('employee.dashboard') }}">
                            <img src="{{ asset('assets/img/ahglogonobg.png') }}" alt="MySystem Logo"
                                style="height: 45px; width: auto; margin-right: 10px;">
                            Al-Hidayah Group HR Portal
                        </a>
                    @endif
                @endauth
            </div>

            <div class="top-nav-items d-flex align-items-center">
                {{-- Notification bell icon --}}
                <div class="nav-item dropdown">
                    <a class="nav-link" id="notificationBell" data-bs-toggle="dropdown" href="#">
                        <i class="bi bi-bell fs-5"></i>

                        @if (auth()->user()->unreadNotifications->count())
                            <span class="badge bg-danger" id="notificationCount">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-end">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            @php
                                $link =
                                    $notification->type === 'App\Notifications\EventReminderNotification'
                                        ? route('event.index.admin')
                                        : route('announcement.index.admin');
                            @endphp
                            <a href="{{ $link }}" class="dropdown-item">
                                <strong>{{ $notification->data['title'] ?? $notification->data['message'] }}</strong>
                                <br>
                                <small>{{ $notification->data['message'] ?? $notification->data['content'] }}</small>
                                <br>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </a>
                        @empty
                            <span class="dropdown-item text-muted">No notifications</span>
                        @endforelse
                    </div>
                </div>

                @auth
                    @if (Auth::user()->role_id !== 2)
                        <!-- Punch In / Out Link -->
                        <a href="#" id="punchLink" class="punch-link me-3"
                            data-punch-in-url="{{ route('attendance.punchIn') }}"
                            data-punch-out-url="{{ route('attendance.punchOut') }}"
                            data-punched="{{ isset($isPunchedIn) && $isPunchedIn ? 'true' : 'false' }}">
                            <i id="punchIcon"
                                class="bi {{ isset($isPunchedIn) && $isPunchedIn ? 'bi-person-x' : 'bi-person-check' }}"></i>
                            <span
                                id="punchText">{{ isset($isPunchedIn) && $isPunchedIn ? 'Punch Out' : 'Punch In' }}</span>
                        </a>
                    @endif

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
                    <a class="nav-link {{ request()->routeIs('admin.dashboard', 'admin.profile*') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-house-door"></i>
                        <span>Dashboard</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.attendance*') ? 'active' : '' }}"
                        href="{{ route('admin.attendance') }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Attendance</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('leave*') ? 'active' : '' }}"
                        href="{{ route('leave.index.admin') }}">
                        <i class="bi bi-airplane"></i>
                        <span>Leave</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('calendar.index') ? 'active' : '' }}"
                        href="{{ route('calendar.index') }}">
                        <i class="bi bi-calendar3"></i>
                        <span>Calendar</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('event*') ? 'active' : '' }}"
                        href="{{ route('event.index.admin') }}">
                        <i class="bi bi-calendar-event"></i>
                        <span>Event</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('task*', 'project*') ? 'active' : '' }}"
                        href="{{ route('task.index.admin') }}">
                        <i class="bi bi-ui-checks"></i>
                        <span>Task & Project</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('form*') ? 'active' : '' }}"
                        href="{{ route('form.admin') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Form</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.requests*') ? 'active' : '' }}"
                        href="{{ route('admin.requests') }}">
                        <i class="bi bi-clipboard"></i>
                        <span>Request</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.employee*', 'profile*') ? 'active' : '' }}"
                        href="{{ route('admin.employee') }}">
                        <i class="bi bi-people"></i>
                        <span>Employee</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('announcement*') ? 'active' : '' }}"
                        href="{{ route('announcement.index.admin') }}">
                        <i class="bi bi-megaphone"></i>
                        <span>Announcement</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }}"
                        href="{{ route('settings.index') }}">
                        <i class="bi bi-gear"></i>
                        <span>System Setting</span>
                    </a>

                    <!-- Employee Navigation (Role ID: 3) -->
                @elseif (Auth::user()->role_id !== '2')
                    <a class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}"
                        href="{{ route('employee.dashboard') }}">
                        <i class="bi bi-house-door"></i>
                        <span>Dashboard</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('employee.attendance*') ? 'active' : '' }}"
                        href="{{ route('employee.attendance') }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Attendance</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('leave*') ? 'active' : '' }}"
                        href="{{ route('leave.index.employee') }}">
                        <i class="bi bi-airplane"></i>
                        <span>Leave</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('calendar.index') ? 'active' : '' }}"
                        href="{{ route('calendar.index') }}">
                        <i class="bi bi-calendar3"></i>
                        <span>Calendar</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('event*') ? 'active' : '' }}"
                        href="{{ route('event.index.employee') }}">
                        <i class="bi bi-calendar-event"></i>
                        <span>Event</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('task*', 'project*') ? 'active' : '' }}"
                        href="{{ route('task.index.employee') }}">
                        <i class="bi bi-ui-checks"></i>
                        <span>Task & Project</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('form*') ? 'active' : '' }}"
                        href="{{ route('form.myforms') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Form</span>
                    </a>
                    @if (Auth::user()->role_id == '4' || Auth::user()->role_id == '5' || Auth::user()->role_id == '6')
                        <a class="nav-link {{ request()->routeIs('form*') ? 'active' : '' }}"
                            href="{{ route('form.employee') }}">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Form</span>
                        </a>
                    @endif
                    <a class="nav-link {{ request()->routeIs('employee.myrequests*') ? 'active' : '' }}"
                        href="{{ route('employee.myrequests') }}">
                        <i class="bi bi-clipboard"></i>
                        <span>Request</span>
                    </a>
                    @if (Auth::user()->role_id == '4' || Auth::user()->role_id == '5' || Auth::user()->role_id == '6')
                        <a class="nav-link {{ request()->routeIs('employee.requests*') ? 'active' : '' }}"
                            href="{{ route('employee.requests') }}">
                            <i class="bi bi-clipboard"></i>
                            <span>Request</span>
                        </a>
                    @endif
                    <a class="nav-link {{ request()->routeIs('announcement*') ? 'active' : '' }}"
                        href="{{ route('announcement.index.employee') }}">
                        <i class="bi bi-megaphone"></i>
                        <span>Announcement</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}"
                        href="{{ route('profile.show') }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Profile</span>
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- FLASH MESSAGES --}}
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Wrapper -->
    <div class="flex-grow-1 d-flex">
        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <div class="container-fluid py-3">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-3 mt-4 text-muted">
        <div class="container">
            &copy; {{ date('Y') }} AHG HR Portal. All Rights Reserved. Teha's
        </div>
    </footer>
</body>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/feather.min.js') }}"></script>
<script src="{{ asset('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="/assets/plugins/simple-calendar/jquery.simple-calendar.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ical.js/1.4.0/ical.min.js"></script>

<script src="/assets/js/calander.js"></script>

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
        const bell = document.getElementById('notificationBell');

        if (bell) {
            bell.addEventListener('show.bs.dropdown', function() {
                fetch("{{ route('notifications.readAll') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                });

                // instantly hide badge (UX)
                const badge = document.getElementById('notificationCount');
                if (badge) badge.remove();
            });
        }
    });
</script>

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

</html>
