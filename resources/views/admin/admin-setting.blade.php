@extends('layouts.master')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">System Settings</h3>

    {{-- ===================== GENERAL SETTINGS ===================== --}}
    <form method="POST" action="{{ route('admin.settings.general') }}" class="card mb-4">
        @csrf
        <div class="card-header fw-semibold">Attendance Rules</div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Max Time Slip (hours)</label>
                <input type="number"
                       class="form-control"
                       name="max_timeslip_hours"
                       value="{{ $settings['max_timeslip_hours'] ?? 3 }}">
            </div>

            <button class="btn btn-primary">Save</button>
        </div>
    </form>

    {{-- ===================== LEAVE ENTITLEMENTS ===================== --}}
    <form method="POST" action="{{ route('admin.settings.leave') }}" class="card mb-4">
        @csrf
        <div class="card-header fw-semibold">Leave Entitlements</div>
        <div class="card-body">

            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th width="200">Days / Year</th>
                    </tr>
                </thead>
                <tbody id="leave-entitlements">
                    @foreach ($leaveEntitlements as $i => $leave)
                        <tr>
                            <td>
                                <input type="text"
                                       class="form-control"
                                       name="entitlements[{{ $i }}][name]"
                                       value="{{ $leave->name }}">
                            </td>
                            <td>
                                <input type="number"
                                       step="0.5"
                                       class="form-control"
                                       name="entitlements[{{ $i }}][days]"
                                       value="{{ $leave->full_entitlement }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addLeave()">+ Add Leave</button>
            <br>
            <button class="btn btn-primary">Save</button>
        </div>
    </form>

    {{-- ===================== MASTER DATA ===================== --}}
    <form method="POST" action="{{ route('admin.settings.master') }}" class="card mb-4">
        @csrf
        <div class="card-header fw-semibold">Master Data</div>
        <div class="card-body">

            @php
                $blocks = [
                    'Event Categories'    => ['event_categories', $eventCategories],
                    'Employment Types'    => ['employment_types', $employmentTypes],
                    'Employment Statuses' => ['employment_statuses', $employmentStatuses],
                    'Company Branches'    => ['company_branches', $companyBranches],
                    'Departments'         => ['departments', $departments],
                ];
            @endphp

            @foreach ($blocks as $title => [$name, $items])
                <div class="mb-4">
                    <label class="form-label fw-semibold">{{ $title }}</label>
                    <div id="{{ $name }}">
                        @foreach ($items as $item)
                            <input type="text"
                                   class="form-control mb-2"
                                   name="{{ $name }}[]"
                                   value="{{ $item->name }}">
                        @endforeach
                    </div>
                    <button type="button"
                            class="btn btn-sm btn-secondary"
                            onclick="addField('{{ $name }}')">+ Add</button>
                </div>
            @endforeach

            <button class="btn btn-primary">Save</button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    function addField(id) {
        document.getElementById(id)
            .insertAdjacentHTML('beforeend',
                `<input type="text" class="form-control mb-2" name="${id}[]">`
            );
    }

    function addLeave() {
        document.getElementById('leave-entitlements')
            .insertAdjacentHTML('beforeend',
                `<tr>
                    <td><input class="form-control" name="entitlements[][name]"></td>
                    <td><input class="form-control" type="number" step="0.5" name="entitlements[][days]"></td>
                </tr>`
            );
    }
</script>
@endpush
