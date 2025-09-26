@extends('layouts.master')

@section('styles')
    <style>
        .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1.5rem;
        }

        .profile-info {
            text-align: center;
        }

        .profile-avatar img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-info .text-muted {
            margin-bottom: 0.2rem;
            /* Reduce space below position */
        }

        .location-job {
            margin-top: 0.2rem;
            /* Optional: add a little space above location */
        }

        hr {
            margin: 1rem 0;
            border-top: 1px solid #e3e6ea;
        }
        
        .btn-edit {
            background-color: #ffc107;
            border: none;
            color: #212529;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
    @yield('styles')
    <div class="row">
        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body d-flex flex-column align-items-center">

                    <div class="profile-avatar position-relative">
                        <img src="{{ asset('assets/img/avatar.png') }}" alt="User Avatar" />
                    </div>
                    <div class="profile-info text-center mb-2">
                        <h2 class="fw-bold mb-1">{{ $employee->full_name ?? '-' }}</h2>
                        <div class="text-muted mb-1">{{ $employee->position ?? 'Employee' }}</div>
                        <div class="text-muted mb-2">{{ $employee->department ?? 'IT Department' }}</div>
                    </div>

                    <hr class="w-100 my-3" style="border-top: 1px solid #e3e6ea;">
                    <div class="row w-100 text-center">
                        <div class="col-6">
                            <div class="text-muted small">Employee ID:</div>
                            <div class="fw-bold">{{ $employee->employee_id ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Join Date:</div>
                            <div class="fw-bold">{{ $employee->join_date ?? '-' }}</div>
                        </div>
                    </div>

                    <button class="btn-edit mt-4" onclick="window.location='{{ route('profile.edit') }}'">
                        Edit Profile
                    </button>
                </div>
            </div>
        </div>


        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Personal Information</h4>

                    <!-- First Row: Full Name and Department -->
                    <div class="row mb-3">
                        <!-- Full Name Column -->
                        <div class="col-6">
                                <div class="text-muted small mb-1">Full Name</div>
                                <div class="d-flex">
                                    <div class="text-muted me-2">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>{{ $employee->full_name ?? '-' }}</div>
                                </div>
                            </div>

                        <!-- Email Column -->
                        <div class="col-6">
                                <div class="text-muted small mb-1">Department</div>
                                <div class="d-flex">
                                    <div class="text-muted me-2">
                                        <i class="bi bi-briefcase"></i>
                                    </div>
                                    <div>{{ $employee->department ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Row: Phone Number and Email -->
                    <div class="row mb-3">
                        <!-- Email Column -->
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <div class="d-flex flex-column">
                                <div class="text-muted small">Email Address</div>
                                <div class="d-flex">
                                    <div class="text-muted me-2">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div>{{ $employee->email ?? '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Phone Number Column -->
                        <div class="col-12 col-md-6">
                            <div class="d-flex flex-column">
                                <div class="text-muted small mb-1">Phone Number</div>
                                <div class="d-flex">
                                    <div class="text-muted me-2">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                    <div>{{ $employee->phone_number ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                        <!-- Third Row: Address -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex flex-column">
                                    <div class="text-muted small mb-1">Address</div>
                                    <div class="d-flex">
                                        <div class="text-muted me-2">
                                            <i class="bi bi-geo-alt me-2"></i>
                                        </div>
                                        <span>{{ $employee->address ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs" id="profileTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal"
                    type="button" role="tab" aria-controls="personal" aria-selected="true">Personal</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="employment-tab" data-bs-toggle="tab" data-bs-target="#employment"
                    type="button" role="tab" aria-controls="employment" aria-selected="false">Employment</button>
            </li>
        </ul>

        <div class="tab-content border border-top-0 rounded-bottom p-4 bg-white shadow-sm" id="profileTabContent"
            style="min-height: 500px;">
            {{-- Personal Tab --}}
            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                <h3 class="section-header">
                    Profile Details
                </h3>
                @foreach ([
                'Full Name' => $employee->full_name,
                'Department' => $employee->department,
                'Position' => $employee->position,
                'Email address' => $employee->email,
                'Phone Number' => $employee->phone_number,
                'Address' => $employee->address,
                'IC Number' => $employee->ic_number,
                'Marital Status' => $employee->marital_status,
                'Gender' => $employee->gender,
                'Birthday' => $employee->birthday,
                'Nationality' => $employee->nationality,
                'Emergency Contact' => $employee->emergency_contact,
                ] as $label => $value)
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">{{ $label }}</div>
                        <div class="col-sm-8">{{ $value ?? '-' }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Employment Tab --}}
            <div class="tab-pane fade" id="employment" role="tabpanel" aria-labelledby="employment-tab">
                <h3 class="section-header">
                    Employment Details
                </h3>
                @foreach ([
                'Full Name' => $employee->full_name,
                'Department' => $employee->department,
                'Position' => $employee->position,
                'Email address' => $employee->email,
                'Phone Number' => $employee->phone_number,
                'Address' => $employee->address,
                'IC Number' => $employee->ic_number,
                'Marital Status' => $employee->marital_status,
                'Gender' => $employee->gender,
                'Birthday' => $employee->birthday,
                'Nationality' => $employee->nationality,
                'Emergency Contact' => $employee->emergency_contact,
                ] as $label => $value)
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">{{ $label }}</div>
                        <div class="col-sm-8">{{ $value ?? '-' }}</div>
                    </div>
                @endforeach
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Bootstrap 5 tab initialization if needed - Bootstrap 5 auto handles tabs with data-bs-toggle="tab"
    </script>
@endsection
