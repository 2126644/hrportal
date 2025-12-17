@extends('layouts.master')

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Dashboard</a></li>
                                        @if ($role_id == 2)
                                            <li class="breadcrumb-item"><a href="{{ route('announcement.index.admin') }}">Announcements</a></li>
                                        @else
                                            <li class="breadcrumb-item"><a href="{{ route('announcement.index.employee') }}">Announcements</a></li>
                                        @endif
                                        <li class="breadcrumb-item active" aria-current="page">Make Announcement</li>
                                    </ol>
                                </nav>
                                <h3 class="page-title"><br>New Announcement</h3>
                                <p class="text-muted">Create a new announcement for the team.</p>
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

                    <form action="{{ route('announcement.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="mb-3">
                            {{-- mb-3 = margin-bottom 1rem
                            mt-3 = margin-top 1rem
                            g-3 = gap 1rem --}}
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control"
                                placeholder="Title of the announcement" value="{{ old('title') }}" required>
                            {{-- Using for= links the label to the input’s id, so clicking the label focuses the input. --}}
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span
                                    class="text-danger">*</span></label>
                            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Describe the announcement"
                                required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="expires_date" class="form-label">Expires Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" id="expires_date" name="expires_date" class="form-control"
                                    value="{{ old('expires_date') }}" required>
                                @error('expires_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select id="category" name="category" class="form-select" required>
                                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>Select category
                                    </option>
                                    @php
                                        $categories = [
                                            'general',
                                            'policy',
                                            'system',
                                            'urgent',
                                            'other',
                                        ];
                                    @endphp
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat }}"
                                            {{ old('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="organizer" class="form-label">Created by <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="organizer" name="organizer" class="form-control"
                                    placeholder="Organizer name" value="{{ old('organizer') }}" required>
                                @error('organizer')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="priority" class="form-label">Priority <span
                                        class="text-danger">*</span></label>
                                <select id="priority" name="priority" class="form-select" required>
                                    @php
                                        $statuses = ['high', 'medium', 'low'];
                                    @endphp
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ old('priority', 'medium') === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="target_group" class="form-label">Target Group <span
                                        class="text-danger">*</span></label>
                                <select id="target_group" name="target_group" class="form-select" required>
                                    @php
                                        $statuses = ['high', 'medium', 'low'];
                                    @endphp
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ old('target_group', 'medium') === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                @error('target_group')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="d-flex justify-content-end">
                            @if ($role_id == 2)
                                <a href="{{ route('announcement.index.admin') }}" class="btn btn-secondary me-2">
                                    Cancel
                                </a>
                            @else
                                <a href="{{ route('announcement.index.employee') }}" class="btn btn-secondary me-2">
                                    Cancel
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                Create Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
