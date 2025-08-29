<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Al-Hidayah HR Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        .role-card {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
            border-radius: 10px;
        }
        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 25px rgba(0,0,0,0.15);
        }
        .feature-list li {
            margin-bottom: 10px;
        }
        footer {
            background-color: #343a40;
            color: #adb5bd;
            padding: 20px 0;
        }
    </style>
</head>
<body>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">Al-Hidayah HR Portal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
                aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#login">Log In</a></li>
                <li class="nav-item"><a class="nav-link" href="#signup">Sign Up</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="py-5 text-center bg-primary text-white">
    <div class="container">
        <h1 class="display-4 fw-bold">Welcome to the Al-Hidayah HR Portal</h1>
        <p class="lead mb-4">Simplify attendance, leaves, tasks, and administrative workflows all in one place.</p>
        <a href="#roles" class="btn btn-light btn-lg">Explore Features</a>
    </div>
</section>

<!-- Roles & Features Section -->
<section id="roles" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">User Roles & Features</h2>
        <div class="row g-4">
            <!-- Employee Card -->
            <div id="employee" class="col-md-4">
                <div class="card role-card h-100 p-4">
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title text-primary mb-3">Employee</h3>
                        <p class="card-text flex-grow-1">Manage your daily work activities with ease.</p>
                        <ul class="feature-list list-unstyled">
                            <li><i class="bi bi-check-circle-fill text-success"></i> Mark Attendance (On-site & Off-site)</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Apply Leave</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> View Leave Balance</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> View Tasks</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Complete Task</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Manager Card -->
            <div id="manager" class="col-md-4">
                <div class="card role-card h-100 p-4">
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title text-success mb-3">Manager</h3>
                        <p class="card-text flex-grow-1">Oversee your team’s productivity and leave requests.</p>
                        <ul class="feature-list list-unstyled">
                            <li><i class="bi bi-check-circle-fill text-success"></i> Approve Leave</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Assign Task</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> View Pending Tasks</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Admin Card -->
            <div id="admin" class="col-md-4">
                <div class="card role-card h-100 p-4">
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title text-danger mb-3">Admin</h3>
                        <p class="card-text flex-grow-1">Manage the system settings and user accounts.</p>
                        <ul class="feature-list list-unstyled">
                            <li><i class="bi bi-check-circle-fill text-success"></i> Manage Users</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> System Reports</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4 fw-bold">About This System</h2>
        <p class="lead text-center mx-auto" style="max-width: 700px;">
            This Employee Management System provides a centralized platform for attendance tracking, leave management, task assignments, and administrative controls. Built for efficiency and ease of use, it empowers employees, managers, and administrators to collaborate and manage workflows seamlessly.
        </p>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4 fw-bold">Contact & Support</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="#" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Your full name" />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="name@example.com" />
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label fw-semibold">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required placeholder="Your message here"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="text-center">
    <div class="container">
        <small>&copy; {{ date('Y') }} Al-Hidayah Group Sdn Bhd. All rights reserved.</small>
    </div>
</footer>

<!-- Bootstrap 5 and Bootstrap Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>

</body>
</html>