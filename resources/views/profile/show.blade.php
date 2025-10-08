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

    <div class="container-fluid">
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
                        <h4 class="employee-name">{{ $employee->full_name ?? 'Employee Name' }}</h4>

                        <!-- Position -->
                        <p class="employee-position text-muted">{{ $employee->position ?? 'Staff' }}</p>

                        <!-- Divider Line -->
                        <hr class="my-4">

                        <!-- Employee ID and Date Joined -->
                        <div class="employee-details">
                            <div class="detail-row d-flex justify-content-between align-items-center">
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
                            <i class="bi bi-pencil-square me-2"></i>Profile Setting
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
                                    <span class="input-group-text border-0">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control"
                                        value="{{ $employee->full_name ?? 'Employee Name' }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="text" class="form-control"
                                        value="{{ $employee->email ?? 'Please add your email' }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="text" class="form-control"
                                        value="{{ $employee->phone_number ?? 'Please add your phone number' }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Department</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0">
                                        <i class="bi bi-building"></i>
                                    </span>
                                    <input type="text" class="form-control"
                                        value="{{ $employee->department ?? 'Please select your department' }}" readonly>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Address</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0">
                                        <i class="bi bi-geo-alt"></i>
                                    </span>
                                    <input type="text" class="form-control"
                                        value="{{ $employee->address ?? 'Please add your address' }}" readonly>
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
                            {{-- add edit button later --}}
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
