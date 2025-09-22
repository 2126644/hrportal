@extends('layouts.master')

@section('content')
    <style>
        body {
            background-color: #f4f8fb;
        }

        .card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .card:hover {
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.08);
        }

        .card-body b {
            font-size: 1.5rem;
            font-weight: 600;
            color: #3498db;
        }

        .card-body g {
            font-size: 1.5rem;
            font-weight: 600;
            color: #40d15d;
        }

        .card-body y {
            font-size: 1.5rem;
            font-weight: 600;
            color: #edd641;
        }

        .btn-info {
            background-color: #5dade2;
            border-color: #5dade2;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 500;
        }

        .btn-info:hover {
            background-color: #3498db;
            border-color: #3498db;
        }

        .card-title {
            font-weight: 600;
            color: #2980b9;
            font-size: 1.25rem;
        }

        .card-header p {
            color: #7f8c8d;
            margin-top: 5px;
            font-size: 0.95rem;
        }

        .db-icon img {
            width: 50px;
            opacity: 0.7;
        }

        .db-widgets {
            padding: 10px;
        }
    </style>

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
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="page-title"><br>Apply for Leave</h3>
                                <p class="text-muted">Submit your leave request for supervisor approval.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body justify-content-between">
                    {{-- makes content flexible row-pushes text left, icon right --}}

                    <form action="{{ route('leave.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        {{-- enctype tells the browser to send the request body in multipart MIME format, necessary for file uploads --}}
                        @csrf

                        <div class="mb-3">
                            <label for="leave_type" class="form-label">Leave Type <span class="text-danger">*</span></label>
                            <select id="leave_type" name="leave_type" class="form-select" required>
                                <option value="" disabled {{ old('leave_type') ? '' : 'selected' }}>Select Type
                                </option>
                                <option value="annual" {{ old('leave_type') === 'annual' ? 'selected' : '' }}>Annual Leave
                                </option>
                                <option value="sick" {{ old('leave_type') === 'sick' ? 'selected' : '' }}>Sick Leave
                                </option>
                                <option value="emergency" {{ old('leave_type') === 'emergency' ? 'selected' : '' }}>
                                    Emergency Leave
                                </option>
                            </select>
                            @error('leave_type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="leave_length" class="form-label">Leave Length <span
                                    class="text-danger">*</span></label>
                            <select id="leave_length" name="leave_length" class="form-select" required>
                                <option value="" disabled {{ old('leave_length') ? '' : 'selected' }}>Select Length</option>
                                <option value="full_day" {{ old('leave_length') === 'full_day' ? 'selected' : '' }}>Full Day
                                </option>
                                <option value="AM" {{ old('leave_length') === 'AM' ? 'selected' : '' }}>AM</option>
                                <option value="PM" {{ old('leave_length') === 'PM' ? 'selected' : '' }}>PM</option>
                            </select>
                            @error('leave_length')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" id="start_date" name="start_date" class="form-control"
                                    value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" id="end_date" name="end_date" class="form-control"
                                    value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                            <textarea id="reason" name="reason" rows="3" class="form-control"
                                placeholder="Describe the reason for your leave" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="attachment" class="form-label">Supporting Document (optional)</label>
                            <input type="file" id="attachment" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">PDF or image (max 2 MB)</small>
                            @error('attachment')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
