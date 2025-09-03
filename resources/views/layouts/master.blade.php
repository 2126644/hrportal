<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>@yield('title', 'MyKICT Smart Study Planner')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logokict2.png') }}">

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
            /* Blue */
            --secondary-color: #0056b3;
            /* Darker blue */
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }

        /* Dark mode specific styles */
        [data-bs-theme="dark"] {
            --primary-color: #0056b3;
            /* Darker blue */
            --dark-color: #f1f2f3;
            --light-color: #2c3e50;
            background-color: #1e1e1e;
            /* Slightly lighter than pure black */
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .content-wrapper {
            background-color: #2a2a2a;
            /* Slightly different from navbar background */
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .navbar {
            background-color: #1e1e1e !important;
        }

        [data-bs-theme="dark"] .nav-link {
            color: #e0e0e0 !important;
        }

        .theme-toggle {
            cursor: pointer;
            background: none;
            border: none;
            color: var(--dark-color);
            font-size: 1.2rem;
            transition: color 0.3s ease;
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
        }

        * {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Modern Navbar */
        .navbar {
            /* background-color: white; */
            background-color: #2980b9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
        }

        .nav-item .nav-link {
            color: white !important;
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
            font-size: 1.5rem;
        }

        [data-bs-theme="dark"] .navbar-brand {
            color: #fff !important;
        }

        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            margin: 0 10px;
            position: relative;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .nav-link i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background-color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
            left: 0;
        }

        .nav-link:hover {
            /* color: var(--primary-color) !important; */
            color: rgb(135, 227, 255) !important;
        }

        /* Main Content Area */
        .content-wrapper {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }

        /* Logout Link */
        .logout-link {
            color: #e74c3c !important;
            transition: color 0.3s ease;
        }

        .logout-link:hover {
            color: #c0392b !important;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar-nav {
                text-align: center;
                background-color: white;
                padding: 1rem;
            }

            .nav-link {
                margin: 10px 0;
                justify-content: center;
            }
        }
    </style>

</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            @auth
            @if(Auth::user()->role_id == '1')
            <!-- Admin clicks brand → go to Admin Dashboard -->
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/img/kictlogo.png') }}" alt="MySystem Logo"
                    style="height: 30px; width: auto; margin-right: 10px; vertical-align: middle;">
                MyKICT Smart Study Planner
            </a>
            @elseif(Auth::user()->role_id == '6')
            <!-- Student clicks brand → go to Student Dashboard -->
            <a class="navbar-brand" href="{{ route('employee.dashboard') }}">
                <img src="{{ asset('assets/img/kictlogo.png') }}" alt="MySystem Logo"
                    style="height: 30px; width: auto; margin-right: 10px; vertical-align: middle;">
                MyKICT Smart Study Planner
            </a>
            @endif
            @endauth
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                    <!-- Admin Navigation (Role ID: 1) -->
                    @if (Auth::user()->role_id == '1')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.courses') }}">
                            <i class="bi bi-book-half"></i> Courses
                        </a>
                    </li>
                    </li>

                    <!-- Student Navigation (Role ID: 6) -->
                    @elseif (Auth::user()->role_id == '6')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('employee.dashboard') }}">
                            <i class="bi bi-house-door-fill"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('employee.attendance') }}">
                            <i class="bi bi-journal-text"></i> Attendance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('employee.leave') }}">
                            <i class="bi bi-calculator-fill"></i> Leave
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('employee.task') }}">
                            <i class="bi bi-person-circle"></i> Task
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('update.profile') }}">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                    </li>
                    @endif


                    {{-- Common Navigation Items --}}
                    <li class="nav-item">
                        <a class="nav-link logout-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                    @endauth
                    {{-- Theme Toggle --}}
                    <li class="nav-item">
                        <button id="themeToggle" class="theme-toggle nav-link">
                            <i class="bi bi-moon-stars-fill"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">

        @yield('content')

    </div>

    <!-- Footer -->
    <footer class="text-center py-3 mt-4 text-muted">
        <div class="container">
            &copy; {{ date('Y') }} MyKICT Smart Study Planner. All Rights Reserved.
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="assets/plugins/simple-calendar/jquery.simple-calendar.js"></script>
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
        // Theme Toggle Script
        document.addEventListener('DOMContentLoaded', (event) => {
            const themeToggle = document.getElementById('themeToggle');
            const htmlTag = document.documentElement;
            const storedTheme = localStorage.getItem('theme');

            // Set initial theme
            if (storedTheme) {
                htmlTag.setAttribute('data-bs-theme', storedTheme);
            }

            // Toggle theme on button click
            themeToggle.addEventListener('click', () => {
                const currentTheme = htmlTag.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                htmlTag.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);

                // Update theme toggle icon
                themeToggle.innerHTML = newTheme === 'dark' ?
                    '<i class="bi bi-sun-fill"></i>' :
                    '<i class="bi bi-moon-stars-fill"></i>';
            });
        });
    </script>
    @stack('scripts')
</body>

</html>