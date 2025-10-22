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
                            <div class="profile-avatar position-relative mx-auto">
                                @if ($employee->profile_pic)
                                    <img src="{{ asset('storage/' . $employee->profile_pic) }}"
                                        alt="{{ $employee->full_name }}" class="rounded-circle img-fluid"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 120px; height: 120px; border: 2px dashed #dee2e6;">
                                        <i class="bi bi-person-fill text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Full Name -->
                        <h4 class="employee-name">{{ $employee->full_name ?? 'Employee Name' }}</h4>

                        <!-- Position -->
                        <p class="employee-position text-muted">{{ $employment->position ?? 'Staff' }}</p>

                        <!-- Divider Line -->
                        <hr class="my-3">

                        <!-- Employee ID and Date Joined -->
                        <div class="employee-details small">
                            <div class="detail-row d-flex justify-content-between align-items-center mb-2">
                                <span class="detail-label text-muted">Employee ID:</span>
                                <span class="detail-value fw-semibold">
                                    {{ $employee->employee_id ?? 'EMP001' }}
                                </span>
                            </div>

                            <div class="detail-row d-flex justify-content-between align-items-center">
                                <span class="detail-label text-muted">Date Joined:</span>
                                <span class="detail-value fw-semibold">
                                    {{ $employment->date_joined ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Edit Profile Button -->
                        <button class="btn btn-primary w-100 mt-3" onclick="window.location='{{ route('profile.edit') }}'">
                            <i class="bi bi-pencil-square me-2"></i>Profile Setting
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Card - Contact Information -->
            <div class="col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4 text-primary">Contact Information</h4>

                        <form class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Full Name</label>
                                <div class="contact-display d-flex align-items-center">
                                    <i class="bi bi-person text-muted me-2"></i>
                                    <span
                                        class="{{ empty($employee->full_name) ? 'text-muted fst-italic' : 'fw-semibold' }}">
                                        {{ $employee->full_name ?? 'Not provided' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Email Address</label>
                                <div class="contact-display d-flex align-items-center">
                                    <i class="bi bi-envelope text-muted me-2"></i>
                                    <span class="{{ empty($employee->email) ? 'text-muted fst-italic' : '' }}">
                                        {{ $employee->email ?? 'Not provided' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Phone Number</label>
                                <div class="contact-display d-flex align-items-center">
                                    <i class="bi bi-telephone text-muted me-2"></i>
                                    <span class="{{ empty($employee->phone_number) ? 'text-muted fst-italic' : '' }}">
                                        {{ $employee->phone_number ?? 'Not provided' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Department</label>
                                <div class="contact-display d-flex align-items-center">
                                    <i class="bi bi-buildings text-muted me-2"></i>
                                    <span class="{{ empty($employment->department) ? 'text-muted fst-italic' : '' }}">
                                        {{ $employment->department ?? 'Not assigned' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold text-muted">Address</label>
                                <div class="contact-display d-flex align-items-start">
                                    <i class="bi bi-geo-alt text-muted me-2 mt-1"></i>
                                    <span class="{{ empty($employee->address) ? 'text-muted fst-italic' : '' }}">
                                        {{ $employee->address ?? 'Not provided' }}
                                    </span>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4">
            <!-- Tabs navigation -->
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
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

            <!-- Tabs content -->
            <div class="tab-content border border-top-0 rounded-bottom p-4 bg-white shadow-sm" id="profileTabsContent"
                style="min-height: 500px;">
                <!-- Details tab -->
                <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">

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

                <!-- Employment Tab -->
                <div class="tab-pane fade" id="employment" role="tabpanel" aria-labelledby="employment-tab">

                    <!-- Employment Information Row -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="section-title mb-3 text-primary">
                                <i class="bi bi-person-vcard me-2"></i>Employment Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Employment Type</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->employment_type) ? 'text-muted' : '' }}">
                                            {{ $employee->employment_type ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Employment Status</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->employment_status) ? 'text-muted' : '' }}">
                                            {{ $employee->employment_status ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Company Branch</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->company_branch) ? 'text-muted' : '' }}">
                                            {{ $employee->company_branch ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Report To</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->report_to) ? 'text-muted' : '' }}">
                                            {{ $employee->report_to ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Department</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->department) ? 'text-muted' : '' }}">
                                            {{ $employee->department ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Position</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->position) ? 'text-muted' : '' }}">
                                            {{ $employee->position ?? '-' }}
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
                                <i class="bi bi-info-circle me-2"></i>Dates Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Date Joined</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->date_joined) ? 'text-muted' : '' }}">
                                            {{ $employee->date_joined ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Probation Start</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->probation_start) ? 'text-muted' : '' }}">
                                            {{ $employee->probation_start ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Probation End</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->probation_end) ? 'text-muted' : '' }}">
                                            {{ $employee->probation_end ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Suspended Start</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->suspended_start) ? 'text-muted' : '' }}">
                                            {{ $employee->suspended_start ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Suspended End</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->suspended_end) ? 'text-muted' : '' }}">
                                            {{ $employee->suspended_end ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Resigned Date</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->resigned_date) ? 'text-muted' : '' }}">
                                            {{ $employee->resigned_date ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-item">
                                        <div class="detail-label text-muted small">Termination Date</div>
                                        <div
                                            class="detail-value fw-semibold {{ empty($employee->termination_date) ? 'text-muted' : '' }}">
                                            {{ $employee->termination_date ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info border-0">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-info-circle me-3 fs-4"></i>
                                    <div>
                                        <h5 class="alert-heading mb-2">Employment Module</h5>
                                        <p class="mb-0">Employment information system is currently in development.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center py-4">
                        <i class="bi bi-tools display-4 text-muted mb-3"></i>
                        <h5 class="text-muted mb-2">Employment Details Coming Soon</h5>
                        <p class="text-muted">Salary information, employment history, and contract details will be
                            available here.</p>
                    </div> --}}
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
        });
    </script>
@endsection
