@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">System Settings</h3>

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf

            <div class="card mb-3">
                <div class="card-header">General</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control"
                            value="{{ $settings['company_name'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">HR Email</label>
                        <input type="email" name="hr_email" class="form-control"
                            value="{{ $settings['hr_email'] ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">System Settings</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Max Time Slip (hours)</label>
                        <input type="number" name="max_timeslip_hours" class="form-control"
                            value="{{ $settings['max_timeslip_hours'] ?? 3 }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Leave Types</label>
                        <div id="leave-types">
                            @foreach ($leaveTypes as $type)
                                <div class="d-flex mb-2">
                                    <input type="text" name="leave_types[]" class="form-control me-2"
                                        value="{{ $type }}" required>
                                    <button type="button" class="btn btn-danger"
                                        onclick="this.parentElement.remove()">✕</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addLeaveType()">
                            + Add Leave Type</button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Event Categories</label>
                        <div id="event-categories">
                            @foreach ($eventCategories as $cat)
                                <div class="d-flex mb-2">
                                    <input type="text" name="event_categories[]" class="form-control me-2"
                                        value="{{ $cat }}" required>
                                    <button type="button" class="btn btn-danger"
                                        onclick="this.parentElement.remove()">✕</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary mb-4" onclick="addEventCategory()">
                            + Add Event Category</button>
                    </div>

                    <button class="btn btn-primary">Save Settings</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function addLeaveType() {
            document.getElementById('leave-types').insertAdjacentHTML(
                'beforeend',
                `<div class="d-flex mb-2">
            <input type="text" name="leave_types[]" class="form-control me-2">
            <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">✕</button>
        </div>`
            );
        }

        function addEventCategory() {
            document.getElementById('event-categories').insertAdjacentHTML(
                'beforeend',
                `<div class="d-flex mb-2">
            <input type="text" name="event_categories[]" class="form-control me-2">
            <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">✕</button>
        </div>`
            );
        }
    </script>
@endpush
