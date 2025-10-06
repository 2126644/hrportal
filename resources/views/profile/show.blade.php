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
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <h3 class="page-title"><br>Profile</h3>
                                <p class="text-muted">Manage your personal information and settings.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <!-- Top Two Cards - Equal Height -->
        <div class="row mb-4">
            <!-- Left Card - Profile Picture and Basic Info -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center p-4">
                        <!-- Profile Picture -->
                        <div class="profile-pic mb-4">
                            <div class="profile-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <h4 class="employee-name mb-2">{{ $employee->full_name ?? 'Employee Name' }}</h4>

                        <!-- Position -->
                        <p class="employee-position text-muted mb-3">{{ $employee->position ?? 'Staff' }}</p>

                        <!-- Divider Line -->
                        <hr class="my-4">

                        <!-- Employee ID and Date Joined -->
                        <div class="employee-details mb-4">
                            <div class="detail-row d-flex justify-content-between align-items-center mb-3">
                                <span class="detail-label text-muted">Employee ID:</span>
                                <span
                                    class="detail-value fw-semibold {{ empty($employee->employee_id) ? 'text-muted' : '' }}">
                                    {{ $employee->employee_id ?? 'EMP001' }}
                                </span>
                            </div>

                            <div class="detail-row d-flex justify-content-between align-items-center">
                                <span class="detail-label text-muted">Date Joined:</span>
                                <span
                                    class="detail-value fw-semibold {{ empty($employee->date_joined) ? 'text-muted' : '' }}">
                                    {{ $employee->date_joined ? \Carbon\Carbon::parse($employee->date_joined)->format('M j, Y') : '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Edit Profile Button -->
                        <button class="btn-edit-profile w-100" onclick="window.location='{{ route('profile.edit') }}'">
                            <i class="bi bi-pencil-square me-2"></i>Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Card - Contact Information -->
            <div class="col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4 text-primary">Personal Information</h4>

                        <form class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $employee->full_name ?? 'John Doe' }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $employee->email ?? 'john.doe@example.com' }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $employee->phone_number ?? '+1 (555) 123-4567' }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Department</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-building"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $employee->department ?? 'IT Department' }}" readonly>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-geo-alt"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $employee->address ?? '123 Main St, City, State 12345' }}" readonly>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="card">
            <div class="card-header bg-transparent border-bottom-0">
                <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details"
                            type="button" role="tab" aria-controls="details" aria-selected="true">
                            <i class="bi bi-person-lines-fill me-2"></i>Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="employment-tab" data-bs-toggle="tab" data-bs-target="#employment"
                            type="button" role="tab" aria-controls="employment" aria-selected="false">
                            <i class="bi bi-briefcase me-2"></i>Employment
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="profileTabsContent">
                    <!-- Details Tab - Row by Row Layout -->
                    <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                        <div class="details-section">
                            <!-- Personal Information Row -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="section-title mb-3 text-primary">
                                        <i class="bi bi-person-vcard me-2"></i>Personal Information
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="detail-item">
                                                <div class="detail-label text-muted small">Gender</div>
                                                <div
                                                    class="detail-value fw-semibold {{ empty($employee->gender) ? 'text-muted' : '' }}">
                                                    {{ $employee->gender ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="detail-item">
                                                <div class="detail-label text-muted small">Birthday</div>
                                                <div
                                                    class="detail-value fw-semibold {{ empty($employee->birthday) ? 'text-muted' : '' }}">
                                                    {{ $employee->birthday ? \Carbon\Carbon::parse($employee->birthday)->format('F j, Y') : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="detail-item">
                                                <div class="detail-label text-muted small">Marital Status</div>
                                                <div
                                                    class="detail-value fw-semibold {{ empty($employee->marital_status) ? 'text-muted' : '' }}">
                                                    {{ $employee->marital_status ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information Row -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="section-title mb-3 text-primary">
                                        <i class="bi bi-info-circle me-2"></i>Additional Information
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="detail-item">
                                                <div class="detail-label text-muted small">Nationality</div>
                                                <div
                                                    class="detail-value fw-semibold {{ empty($employee->nationality) ? 'text-muted' : '' }}">
                                                    {{ $employee->nationality ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="detail-item">
                                                <div class="detail-label text-muted small">Emergency Contact</div>
                                                <div
                                                    class="detail-value fw-semibold {{ empty($employee->emergency_contact) ? 'text-muted' : '' }}">
                                                    {{ $employee->emergency_contact ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="detail-item">
                                                <div class="detail-label text-muted small">IC Number</div>
                                                <div
                                                    class="detail-value fw-semibold {{ empty($employee->ic_number) ? 'text-muted' : '' }}">
                                                    {{ $employee->ic_number ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Tab -->
                    <div class="tab-pane fade" id="employment" role="tabpanel" aria-labelledby="employment-tab">
                        <div class="text-center py-5">
                            <i class="bi bi-briefcase display-1 text-muted mb-3"></i>
                            <h4 class="text-muted">Employment Information</h4>
                            <p class="text-muted">Employment details will be displayed here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Profile Page Specific Styles */
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid var(--primary-color);
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #6c757d;
            margin: 0 auto;
        }

        .employee-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .employee-position {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .detail-row {
            padding: 0.5rem 0;
        }

        .detail-label {
            font-size: 0.9rem;
        }

        .detail-value {
            font-size: 0.95rem;
        }

        .btn-edit-profile {
            background-color: var(--primary-color);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-edit-profile:hover {
            background-color: #0056b3;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .info-icon-small {
            font-size: 1.25rem;
            margin-right: 1rem;
            width: 24px;
            text-align: center;
            margin-top: 0.25rem;
            flex-shrink: 0;
        }

        .info-content-horizontal {
            flex: 1;
        }

        .info-value-box {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: #495057;
            width: 100%;
            min-height: 40px;
            display: flex;
            align-items: center;
        }

        .empty-value {
            color: #6c757d !important;
            font-style: italic;
        }

        .detail-item {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .detail-item:hover {
            background: #e9ecef;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 1rem 1.5rem;
            border-radius: 8px 8px 0 0;
        }

        .nav-tabs .nav-link.active {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .nav-tabs .nav-link:hover {
            border: none;
            color: var(--primary-color);
        }

        /* Page Header Styling - Remove border */
        .page-sub-header {
            padding-bottom: 0;
            margin-bottom: 2rem;
            border-bottom: none !important;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2980b9;
            margin-bottom: 0.5rem;
        }

        /* Dark mode adjustments */
        [data-bs-theme="dark"] .detail-item {
            background: #2a2a2a;
            color: #e0e0e0;
            border-color: #444;
        }

        [data-bs-theme="dark"] .detail-item:hover {
            background: #333;
        }

        [data-bs-theme="dark"] .info-value-box {
            background: #1a1a1a;
            border-color: #444;
            color: #e0e0e0;
        }

        [data-bs-theme="dark"] .text-muted {
            color: #adb5bd !important;
        }

        [data-bs-theme="dark"] .employee-name {
            color: #f8f9fa;
        }


        /* Ensure equal height for top cards */
        .h-100 {
            height: 100% !important;
        }

        .card-body {
            display: flex;
            flex-direction: column;
        }

        .input-group-text i {
                color: #6c757d;
            }

            .form-control[readonly] {
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 6px;
                box-shadow: none;
            }

            .input-group-text {
                border: 1px solid #dee2e6;
                border-right: none;
                border-radius: 6px 0 0 6px;
            }

            .form-label {
                color: #2c3e50;
            }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-avatar {
                width: 100px;
                height: 100px;
                font-size: 2rem;
            }

            .employee-name {
                font-size: 1.3rem;
            }

            .info-icon-small {
                font-size: 1.1rem;
                margin-right: 0.75rem;
            }

            .btn-edit-profile {
                padding: 0.6rem 1.25rem;
                font-size: 0.9rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const triggerTabList = [].slice.call(document.querySelectorAll('#profileTabs button'))
            triggerTabList.forEach(function(triggerEl) {
                const tabTrigger = new bootstrap.Tab(triggerEl)

                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            })

            // Edit profile button functionality
            const editProfileBtn = document.querySelector('.btn-edit-profile');
            if (editProfileBtn) {
                editProfileBtn.addEventListener('click', function() {
                    // The onclick handler already handles the navigation
                    console.log('Edit profile clicked');
                });
            }
        });
    </script>
@endsection
