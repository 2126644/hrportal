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
                                <h3 class="page-title"><br>New Event</h3>
                                <p class="text-muted">Create a new event or program for the team.</p>
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

                    <form action="{{ route('event.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="mb-3">
                            {{-- mb-3 = margin-bottom 1rem
                            mt-3 = margin-top 1rem
                            g-3 = gap 1rem --}}
                            <label for="event_name" class="form-label">Event Name <span class="text-danger">*</span></label>
                            <input type="text" id="event_name" name="event_name" class="form-control"
                                placeholder="Name of the event" value="{{ old('event_name') }}" required>
                            {{-- Using for="event_name" links the label to the input’s id, so clicking the label focuses the input. --}}
                            @error('event_name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="event_date" class="form-label">Event Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" id="event_date" name="event_date" class="form-control"
                                    value="{{ old('event_date') }}" required>
                                @error('event_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="event_time" class="form-label">Event Time <span
                                        class="text-danger">*</span></label>
                                <input type="time" id="event_time" name="event_time" class="form-control"
                                    value="{{ old('event_time') }}" required>
                                @error('event_time')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="event_location" class="form-label">Location <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="event_location" name="event_location" class="form-control"
                                placeholder="Location of the event" value="{{ old('event_location') }}" required>
                            @error('event_location')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span
                                    class="text-danger">*</span></label>
                            <textarea id="description" name="description" class="form-control" rows="4"
                                placeholder="Describe the event" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select id="category" name="category" class="form-select" required>
                                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>Select category
                                    </option>
                                    @php
                                        $categories = [
                                            'meeting',
                                            'conference',
                                            'workshop',
                                            'networking',
                                            'webinar',
                                            'social',
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

                            <div class="col-md-3">
                                <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                                <input type="number" id="capacity" name="capacity" class="form-control" min="1"
                                    placeholder="Max attendees" value="{{ old('capacity') }}" required>
                                @error('capacity')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="price" class="form-label">Price (MYR)</label>
                                <input type="number" step="0.01" min="0" id="price" name="price"
                                    class="form-control" placeholder="0.00" value="{{ old('price', '0.00') }}">
                                @error('price')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="organizer" class="form-label">Organizer <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="organizer" name="organizer" class="form-control"
                                    placeholder="Organizer name" value="{{ old('organizer') }}" required>
                                @error('organizer')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="event_status" class="form-label">Event Status <span
                                        class="text-danger">*</span></label>
                                <select id="event_status" name="event_status" class="form-select" required>
                                    @php
                                        $statuses = ['upcoming', 'ongoing', 'completed', 'cancelled'];
                                    @endphp
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ old('event_status', 'upcoming') === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                @error('event_status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags <small class="text-muted">(comma
                                    separated)</small></label>
                            <input type="text" id="tags" name="tags" class="form-control"
                                placeholder="e.g. tech, networking, free" value="{{ old('tags') }}">
                            @error('tags')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Event Image</label>
                            <input type="file" id="image" name="image" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">JPG, JPEG or PNG (max 2 MB)</small>
                            @error('image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="rsvp_required" name="rsvp_required"
                                value="1" {{ old('rsvp_required') ? 'checked' : '' }}>
                            <label class="form-check-label" for="rsvp_required">Require RSVP</label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
