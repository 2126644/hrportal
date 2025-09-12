<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>@yield('title', 'Al-Hidayah Group HR Portal')</title>

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

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Simple Calendar (student dashboard page) -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/simple-calendar/simple-calendar.css') }}">

    <!-- DataTables (admin courses page) -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">

    <!-- Bootstrap Icons from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">

    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #0056b3;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --sidebar-width: 280px;
        }

        /* Dark mode specific styles */
        [data-bs-theme="dark"] {
            --primary-color: #0056b3;
            --dark-color: #f1f2f3;
            --light-color: #2c3e50;
            background-color: #1e1e1e;
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .content-wrapper {
            background-color: #2a2a2a;
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .top-navbar {
            background-color: #1e1e1e !important;
            border-bottom: 1px solid #333 !important;
        }

        [data-bs-theme="dark"] .sidebar {
            background-color: #1a1a1a !important;
            border-right: 1px solid #333 !important;
        }

        [data-bs-theme="dark"] .sidebar .nav-link {
            color: #e0e0e0 !important;
        }

        [data-bs-theme="dark"] .sidebar .nav-link:hover {
            background-color: #333 !important;
            color: #87e3ff !important;
        }

        [data-bs-theme="dark"] .sidebar-overlay {
            background-color: rgba(0, 0, 0, 0.7) !important;
        }

        .theme-toggle {
            cursor: pointer;
            background: none;
            border: none;
            color: var(--dark-color);
            font-size: 1.2rem;
            transition: color 0.3s ease;
            margin-left: 2rem;
        }

        .theme-toggle:hover {
            color: var(--primary-color);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2980b9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f4f6f7;
            line-height: 1.6;
            font-family: 'Poppins', sans-serif !important;
            padding-top: 70px;
        }

        * {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Top Navbar (minimal) */
        .top-navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            border-bottom: 1px solid #e0e0e0;
        }

        .navbar-brand {
            font-weight: 700;
            color: #2980b9 !important;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }

        [data-bs-theme="dark"] .navbar-brand {
            color: #fff !important;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-color);
            cursor: pointer;
            margin-right: 1rem;
            transition: color 0.3s ease;
        }

        .sidebar-toggle:hover {
            color: var(--primary-color);
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease;
            z-index: 1040;
            overflow-y: auto;
            border-right: 1px solid #e0e0e0;
        }

        .sidebar.show {
            left: 0;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-color);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .sidebar-close:hover {
            color: var(--primary-color);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 0;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background-color: #f8f9fa;
            color: var(--primary-color) !important;
            padding-left: 2rem;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link:hover::before,
        .sidebar .nav-link.active::before {
            transform: scaleY(1);
        }

        .sidebar .nav-link.active {
            background-color: rgba(0, 123, 255, 0.1);
            color: var(--primary-color) !important;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1035;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Top navbar right items */
        .top-nav-items {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-link {
            color: #e74c3c !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .logout-link:hover {
            color: #c0392b !important;
        }

        .logout-link i {
            margin-right: 0.5rem;
        }

        /* Main Content */
        .main-content {
            transition: margin-left 0.3s ease;
        }

        .content-wrapper {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                left: -100%;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .navbar-brand img {
                height: 35px !important;
            }
        }

        @media (min-width: 769px) {
            .sidebar-overlay {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navigation (minimal) -->
    <nav class="top-navbar">
        {{-- container-fluid spans full width (0 side padding), while .container (used for the content card) has fixed width + padding --}}
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                @auth
                @if(Auth::user()->role_id == '2')
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
            
            <div class="top-nav-items">
                @auth
                <a class="logout-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @endauth
                
                <button id="themeToggle" class="theme-toggle">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/ahglogonobg.png') }}" alt="Logo" style="height: 30px; margin-right: 8px;">
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
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-house-door-fill"></i>
                <span>Dashboard</span>
            </a>
            <a class="nav-link" href="{{ route('admin.courses') }}">
                <i class="bi bi-book-half"></i>
                <span>Courses</span>
            </a>

            <!-- Employee Navigation (Role ID: 3) -->
            @elseif (Auth::user()->role_id == '3')
            <a class="nav-link" href="{{ route('employee.dashboard') }}">
                <i class="bi bi-house-door-fill"></i>
                <span>Dashboard</span>
            </a>
            <a class="nav-link" href="{{ route('employee.attendance') }}">
                <i class="bi bi-clock-history"></i>
                <span>Attendance</span>
            </a>
            <a class="nav-link" href="{{ route('employee.leave') }}">
                <i class="bi bi-calendar3"></i>
                <span>Leave</span>
            </a>
            <a class="nav-link" href="{{ route('employee.task') }}">
                <i class="bi bi-ui-checks"></i>
                <span>Task</span>
            </a>
            <a class="nav-link" href="{{ route('update.profile') }}">
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
        <div class="container">
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
        // Sidebar Toggle Script
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function showSidebar() {
                sidebar.classList.add('show');
                sidebarOverlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function hideSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            sidebarToggle.addEventListener('click', showSidebar);
            sidebarClose.addEventListener('click', hideSidebar);
            sidebarOverlay.addEventListener('click', hideSidebar);

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideSidebar();
                }
            });

            // Highlight active nav link
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });

        // Theme Toggle Script
        document.addEventListener('DOMContentLoaded', (event) => {
            const themeToggle = document.getElementById('themeToggle');
            const htmlTag = document.documentElement;
            const storedTheme = localStorage.getItem('theme');

            // Set initial theme
            if (storedTheme) {
                htmlTag.setAttribute('data-bs-theme', storedTheme);
                updateThemeIcon(storedTheme);
            }

            function updateThemeIcon(theme) {
                themeToggle.innerHTML = theme === 'dark' ?
                    '<i class="bi bi-sun-fill"></i>' :
                    '<i class="bi bi-moon-stars-fill"></i>';
            }

            // Toggle theme on button click
            themeToggle.addEventListener('click', () => {
                const currentTheme = htmlTag.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                htmlTag.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });
        });
    </script>
    @stack('scripts')
</body>

</html>