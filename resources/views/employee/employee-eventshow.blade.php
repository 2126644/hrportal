<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #71b0f8;
            --secondary-color: #f8f9fa;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .event-header {
            background: linear-gradient(135deg, var(--primary-color), #4a90e2);
            color: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .event-image {
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .event-details-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .event-details-card .card-header {
            background-color: white;
            border-bottom: 1px solid #eaeaea;
            font-weight: 600;
            padding: 1rem 1.5rem;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .detail-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .detail-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(113, 176, 248, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: var(--primary-color);
            flex-shrink: 0;
        }
        
        .detail-content {
            flex: 1;
        }
        
        .detail-label {
            font-weight: 500;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .detail-value {
            font-weight: 500;
            color: #212529;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
        }
        
        .status-upcoming {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }
        
        .status-ongoing {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }
        
        .status-completed {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        .status-cancelled {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }
        
        .tag {
            display: inline-block;
            background-color: rgba(113, 176, 248, 0.1);
            color: var(--primary-color);
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 1.5rem;
        }
        
        .btn-rsvp {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .btn-rsvp:hover {
            background-color: #5a9ae6;
            color: white;
        }
        
        .capacity-meter {
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }
        
        .capacity-fill {
            height: 100%;
            background-color: var(--primary-color);
            border-radius: 4px;
        }
        
        @media (max-width: 768px) {
            .event-header {
                padding: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.event') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>Back to Events
            </a>
        </div>
        
        <!-- Event Header -->
        <div class="event-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold mb-3">{{ $event->event_name }}</h1>
                    <div class="d-flex flex-wrap align-items-center mb-3">
                        @php
                            $statusClass = 'status-upcoming';
                            if ($event->event_status === 'ongoing') $statusClass = 'status-ongoing';
                            elseif ($event->event_status === 'completed') $statusClass = 'status-completed';
                            elseif ($event->event_status === 'cancelled') $statusClass = 'status-cancelled';
                        @endphp
                        <span class="status-badge {{ $statusClass }} me-3 text-capitalize">{{ $event->event_status }}</span>
                        <span class="me-3"><i class="far fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</span>
                        <span><i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</span>
                    </div>
                    <p class="lead mb-0">{{ Str::limit($event->description, 150) }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    @if($event->price > 0)
                        <div class="fs-4 fw-bold">${{ number_format($event->price, 2) }}</div>
                        <div class="text-white-50">Registration fee</div>
                    @else
                        <div class="fs-4 fw-bold">Free</div>
                        <div class="text-white-50">No cost to attend</div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Left Column - Event Details -->
            <div class="col-lg-8">
                <!-- Event Image -->
                <div class="card event-details-card mb-4">
                    <div class="card-body p-0">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->event_name }}" class="event-image w-100">
                        @else
                            <div class="event-image w-100 bg-light d-flex align-items-center justify-content-center">
                                <div class="text-center text-muted">
                                    <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                    <p>No event image available</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Event Description -->
                <div class="card event-details-card mb-4">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i>About This Event
                    </div>
                    <div class="card-body">
                        <p>{{ $event->description }}</p>
                    </div>
                </div>
                
                <!-- Event Schedule - Dynamic based on event time -->
                <div class="card event-details-card mb-4">
                    <div class="card-header">
                        <i class="fas fa-list-alt me-2"></i>Event Schedule
                    </div>
                    <div class="card-body">
                        @php
                            $eventTime = \Carbon\Carbon::parse($event->event_time);
                            $eventEndTime = $eventTime->copy()->addHours(2); // Assuming 2-hour duration
                        @endphp
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Registration & Welcome</div>
                                <div class="detail-value">{{ $eventTime->copy()->subMinutes(30)->format('h:i A') }} - {{ $eventTime->format('h:i A') }}</div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-microphone"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Main Event</div>
                                <div class="detail-value">{{ $eventTime->format('h:i A') }} - {{ $eventEndTime->copy()->subMinutes(30)->format('h:i A') }}</div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Networking & Closing</div>
                                <div class="detail-value">{{ $eventEndTime->copy()->subMinutes(30)->format('h:i A') }} - {{ $eventEndTime->format('h:i A') }}</div>
                            </div>
                        </div>
                        
                        <!-- Optional: Add custom schedule items if stored in database -->
                        @if($event->category === 'workshop')
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Hands-on Workshop Session</div>
                                <div class="detail-value">{{ $eventTime->copy()->addMinutes(45)->format('h:i A') }} - {{ $eventEndTime->copy()->subMinutes(45)->format('h:i A') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Event Info & Actions -->
            <div class="col-lg-4">
                <!-- Event Details Card -->
                <div class="card event-details-card mb-4">
                    <div class="card-header">
                        <i class="fas fa-calendar-day me-2"></i>Event Details
                    </div>
                    <div class="card-body">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Date</div>
                                <div class="detail-value">{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="far fa-clock"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Time</div>
                                <div class="detail-value">{{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Location</div>
                                <div class="detail-value">{{ $event->event_location }}</div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Category</div>
                                <div class="detail-value text-capitalize">{{ $event->category }}</div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Organizer</div>
                                <div class="detail-value">{{ $event->organizer }}</div>
                            </div>
                        </div>
                        
                        @if($event->tags)
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Tags</div>
                                <div class="detail-value">
                                    @php
                                        $tags = json_decode($event->tags, true) ?: [];
                                    @endphp
                                    @foreach($tags as $tag)
                                        <span class="tag">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Created By</div>
                                <div class="detail-value">Employee #{{ $event->created_by }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Attendance Card -->
                <div class="card event-details-card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-2"></i>Attendance
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="detail-label">Registered</span>
                            <span class="detail-value">{{ $event->attendees }} / {{ $event->capacity }}</span>
                        </div>
                        <div class="capacity-meter">
                            @php
                                $attendancePercentage = $event->capacity > 0 ? ($event->attendees / $event->capacity) * 100 : 0;
                            @endphp
                            <div class="capacity-fill" style="width: {{ $attendancePercentage }}%"></div>
                        </div>
                        <div class="mt-2">
                            @php
                                $spotsLeft = $event->capacity - $event->attendees;
                            @endphp
                            <small class="text-muted">{{ $spotsLeft }} spots left</small>
                        </div>
                        
                        <div class="action-buttons">
                            @if($event->rsvp_required && $event->event_status === 'upcoming')
                                <button class="btn btn-rsvp flex-fill" id="rsvp-button">
                                    <i class="fas fa-check-circle me-2"></i>RSVP Now
                                </button>
                            @elseif(!$event->rsvp_required)
                                <button class="btn btn-rsvp flex-fill" id="register-button">
                                    <i class="fas fa-user-plus me-2"></i>Register Now
                                </button>
                            @else
                                <button class="btn btn-secondary flex-fill" disabled>
                                    <i class="fas fa-ban me-2"></i>Registration Closed
                                </button>
                            @endif
                            <button class="btn btn-outline-secondary" id="share-button">
                                <i class="far fa-share-square me-2"></i>Share
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Map Placeholder -->
                <div class="card event-details-card">
                    <div class="card-header">
                        <i class="fas fa-map me-2"></i>Location
                    </div>
                    <div class="card-body p-0">
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px; border-radius: 0 0 10px 10px;">
                            <div class="text-center text-muted">
                                <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                                <p>{{ $event->event_location }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle RSVP/Register button click
            const actionButton = document.getElementById('rsvp-button') || document.getElementById('register-button');
            if (actionButton) {
                actionButton.addEventListener('click', function() {
                    // In a real app, you would make an AJAX request here
                    const buttonText = this.id === 'rsvp-button' ? 'RSVP' : 'Registration';
                    alert(`Thank you! Your ${buttonText} has been received.`);
                    this.innerHTML = `<i class="fas fa-check me-2"></i>${buttonText} Confirmed`;
                    this.classList.remove('btn-rsvp');
                    this.classList.add('btn-success');
                    this.disabled = true;
                });
            }
            
            // Handle share button
            document.getElementById('share-button').addEventListener('click', function() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $event->event_name }}',
                        text: 'Check out this event!',
                        url: window.location.href,
                    })
                    .catch(console.error);
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(window.location.href).then(function() {
                        alert('Event link copied to clipboard!');
                    });
                }
            });
        });
    </script>
</body>
</html>