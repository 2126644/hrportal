@extends('layouts.master')

@section('styles')
    <style>
        /* Custom CSS for the profile page */

        .profile-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1.5rem;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background-color: #cfe8f9;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            font-size: 3rem;
            color: #0d6efd;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .edit-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: white;
            color: #0b5ed7;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            border: none;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2rem;
            box-shadow: 0 2px 6px rgb(13 110 253 / 0.5);
            transition: background-color 0.3s ease;
        }

        .profile-info {
            flex-grow: 1;
        }

        .profile-info h2 {
            margin-bottom: 0.2rem;
            font-weight: 700;
            color: #212529;
        }

        .profile-info p {
            margin: 0;
            font-size: 1rem;
            color: #6c757d;
        }

        .profile-info .location-job {
            margin-top: 0.3rem;
            font-size: 0.875rem;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .profile-info .location-job svg {
            width: 16px;
            height: 16px;
            fill: #0d6efd;
        }

        /* Tabs styling */
        .nav-tabs {
            border-bottom: 2px solid #0d6efd;
            margin-bottom: 1.5rem;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border: none;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom-color: #0d6efd;
        }

        /* Section headers */
        .section-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: #0d6efd;
        }

        .section-header svg {
            width: 20px;
            height: 20px;
            fill: #0d6efd;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        table th,
        table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.9rem;
            color: #495057;
        }

        table th {
            background-color: #e9f2ff;
            font-weight: 600;
            color: #0d6efd;
        }

        /* Form styling */
        .form-section {
            background-color: #f4f8fb;
            padding: 1rem 1.5rem;
            border-radius: 6px;
            box-shadow: inset 0 0 10px rgb(13 110 253 / 0.1);
            margin-bottom: 2rem;
        }

        .form-section label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #212529;
        }

        .form-control {
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 4px rgba(13, 110, 253, 0.25);
            outline: none;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        /* Sidebar styling (if included) */
        .sidebar {
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
            min-height: 100vh;
            padding: 1.5rem;
        }

        .sidebar a {
            color: #495057;
            font-weight: 600;
            display: block;
            padding: 0.75rem 0;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar a.active,
        .sidebar a:hover {
            color: #0d6efd;
            border-left-color: #0d6efd;
            background-color: #e9f2ff;
            text-decoration: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-info .location-job {
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
@yield('styles')
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar position-relative">
                {{-- Assuming you have a user avatar image --}}
                <img src="{{ asset('assets/img/avatar.png') }}" alt="User Avatar" />
                <button class="edit-btn" title="Edit Profile">
                    <i class="bi bi-pencil-square fs-6"></i>
                </button>
            </div>
            <div class="profile-info">
                <h2>{{ $employee->full_name ?? '-' }}</h2>
                <p class="text-muted">{{ $employee->position ?? 'Staff' }}</p>
                <div class="location-job">
                    {{-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM12 11.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg> --}}
                    <span>{{ $employee->location ?? 'Al-Hidayah Group Sdn Bhd' }}</span>
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
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="payroll-tab" data-bs-toggle="tab" data-bs-target="#payroll" type="button"
                    role="tab" aria-controls="payroll" aria-selected="false">Payroll</button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabContent">
            {{-- Personal Tab --}}
            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                <div class="form-section">
                    <h3 class="section-header">
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-3-3.87"></path><path d="M4 21v-2a4 4 0 0 1 3-3.87"></path><circle cx="12" cy="7" r="4"></circle></svg> --}}
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
            </div>

            {{-- Employment Tab --}}
            {{-- <div class="tab-pane fade" id="employment" role="tabpanel" aria-labelledby="employment-tab">
            <h3 class="section-header">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 7V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v3"></path></svg>
                Employment
            </h3>
            <table>
                <tbody>
                    <tr>
                        <th>Employment Type:</th>
                        <td>{{ $employment->type ?? 'Internship' }}</td>
                    </tr>
                    <tr>
                        <th>Employment Status:</th>
                        <td>{{ $employment->status ?? 'Other' }}</td>
                    </tr>
                    <tr>
                        <th>Report To:</th>
                        <td>{{ $employment->report_to ?? 'Nohrashizan binti Hashim' }}</td>
                    </tr>
                    <tr>
                        <th>Location:</th>
                        <td>{{ $employment->location ?? 'Junior' }}</td>
                    </tr>
                    <tr>
                        <th>Department:</th>
                        <td>{{ $employment->department ?? 'Software Development' }}</td>
                    </tr>
                    <tr>
                        <th>Position:</th>
                        <td>{{ $employment->position ?? 'Software Developer Intern' }}</td>
                    </tr>
                    <tr>
                        <th>Employment No.:</th>
                        <td>{{ $employment->number ?? '2QAIntern004' }}</td>
                    </tr>
                    <tr>
                        <th>Employment Date:</th>
                        <td>{{ $employment->date ?? '01 08 2025' }}</td>
                    </tr>
                </tbody>
            </table> --}}

            {{-- Employment History Table --}}
            {{-- <h4 class="section-header mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                Employment History
            </h4>
            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Company Name</th>
                        <th>Leave Reason</th>
                        <th>Position</th>
                        <th>Salary (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employmentHistories as $history)
                    <tr>
                        <td>{{ $history->start_date }}</td>
                        <td>{{ $history->end_date }}</td>
                        <td>{{ $history->company_name }}</td>
                        <td>{{ $history->leave_reason }}</td>
                        <td>{{ $history->position }}</td>
                        <td>{{ $history->salary }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No data available in table</td>
                    </tr>
                    @endforelse
                </tbody>
            </table> --}}

            {{-- Employee Document Table --}}
            {{-- <h4 class="section-header mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="9" y2="9"></line></svg>
                Employee Document
            </h4>
            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>File</th>
                        <th>Description</th>
                        <th>Size (MB)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employeeDocuments as $document)
                    <tr>
                        <td><a href="{{ asset('storage/' . $document->file_path) }}" target="_blank">{{ $document->file_name }}</a></td>
                        <td>{{ $document->description }}</td>
                        <td>{{ number_format($document->size_mb, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No data available in table</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div> --}}

            {{-- Payroll Tab --}}
            {{-- <div class="tab-pane fade" id="payroll" role="tabpanel" aria-labelledby="payroll-tab">
            <h3 class="section-header">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                Payroll Information
            </h3>
            <p class="text-muted">Payroll data goes here.</p>
        </div> --}}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Bootstrap 5 tab initialization if needed - Bootstrap 5 auto handles tabs with data-bs-toggle="tab"
    </script>
@endsection
