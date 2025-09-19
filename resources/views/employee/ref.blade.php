@extends('layouts.master')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-8">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Event Management Hub
                </h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">
                    Discover, organize, and attend amazing events. Connect with your community and make every moment count.
                </p>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white bg-opacity-10 backdrop-blur-md rounded-xl p-6 text-center">
                    <i class="fas fa-calendar text-2xl mb-2"></i>
                    <div class="text-2xl font-bold">{{ $stats['total_events'] }}</div>
                    <div class="text-sm opacity-80">Total Events</div>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-md rounded-xl p-6 text-center">
                    <i class="fas fa-chart-line text-2xl mb-2"></i>
                    <div class="text-2xl font-bold">{{ $stats['upcoming_events'] }}</div>
                    <div class="text-sm opacity-80">Upcoming</div>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-md rounded-xl p-6 text-center">
                    <i class="fas fa-users text-2xl mb-2"></i>
                    <div class="text-2xl font-bold">{{ $stats['total_attendees'] }}</div>
                    <div class="text-sm opacity-80">Total Attendees</div>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-md rounded-xl p-6 text-center">
                    <i class="fas fa-map-marker-alt text-2xl mb-2"></i>
                    <div class="text-2xl font-bold">{{ $stats['average_attendance'] }}</div>
                    <div class="text-sm opacity-80">Avg. Attendance</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                {{-- <a href="{{ route('events.create') }}" 
                   class="inline-flex items-center gap-2 bg-white text-blue-600 px-6 py-3 rounded-xl font-medium hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-plus"></i>
                    Create New Event
                </a> --}}
                <div class="flex gap-2">
                    <button onclick="toggleView('grid')" 
                            id="grid-btn"
                            class="p-3 rounded-lg transition-all bg-white bg-opacity-20 text-white">
                        <i class="fas fa-th"></i>
                    </button>
                    <button onclick="toggleView('list')" 
                            id="list-btn"
                            class="p-3 rounded-lg transition-all bg-white bg-opacity-10 text-white hover:bg-opacity-20">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-100">
            <div class="flex items-center gap-2 mb-6">
                <i class="fas fa-filter text-blue-600"></i>
                <h2 class="text-lg font-semibold text-gray-900">Filter Events</h2>
            </div>

            <form method="GET" action="{{ route('employee.event') }}" class="grid md:grid-cols-4 gap-6">
                <!-- Search -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Search Events</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by title, description..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                        <option value="conference" {{ request('category') == 'conference' ? 'selected' : '' }}>Conference</option>
                        <option value="workshop" {{ request('category') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="networking" {{ request('category') == 'networking' ? 'selected' : '' }}>Networking</option>
                        <option value="webinar" {{ request('category') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                        <option value="social" {{ request('category') == 'social' ? 'selected' : '' }}>Social</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Events</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <!-- Price Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <select name="price_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="all" {{ request('price_filter') == 'all' ? 'selected' : '' }}>All Prices</option>
                        <option value="free" {{ request('price_filter') == 'free' ? 'selected' : '' }}>Free Events</option>
                        <option value="paid" {{ request('price_filter') == 'paid' ? 'selected' : '' }}>Paid Events</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Summary -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">
                    @if(request()->hasAny(['search', 'category', 'status', 'price_filter']))
                        Filtered Events ({{ $events->count() }})
                    @else
                        All Events
                    @endif
                </h2>
                <p class="text-gray-600">
                    @if($events->count() === 0)
                        No events match your filters
                    @else
                        Showing {{ $events->count() }} events
                    @endif
                </p>
            </div>
        </div>

        <!-- Events Grid/List -->
        @if($events->count() === 0)
            <div class="text-center py-16">
                <i class="fas fa-calendar text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Events Found</h3>
                <p class="text-gray-500 mb-6">
                    Try adjusting your filters or create a new event to get started.
                </p>
                {{-- <a href="{{ route('events.create') }}" 
                   class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus"></i>
                    Create Your First Event
                </a> --}}
            </div>
        @else
            <div id="events-container" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    @include('events.partials.event-card', ['event' => $event])
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Event Modal -->
<div id="event-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal content will be loaded here -->
    </div>
</div>

<script>
let currentView = 'grid';

function toggleView(view) {
    currentView = view;
    const container = document.getElementById('events-container');
    const gridBtn = document.getElementById('grid-btn');
    const listBtn = document.getElementById('list-btn');
    
    if (view === 'grid') {
        container.className = 'grid md:grid-cols-2 lg:grid-cols-3 gap-6';
        gridBtn.className = 'p-3 rounded-lg transition-all bg-white bg-opacity-20 text-white';
        listBtn.className = 'p-3 rounded-lg transition-all bg-white bg-opacity-10 text-white hover:bg-opacity-20';
    } else {
        container.className = 'space-y-4';
        listBtn.className = 'p-3 rounded-lg transition-all bg-white bg-opacity-20 text-white';
        gridBtn.className = 'p-3 rounded-lg transition-all bg-white bg-opacity-10 text-white hover:bg-opacity-20';
    }
    
    // Update all event cards
    const cards = container.querySelectorAll('.event-card');
    cards.forEach(card => {
        if (view === 'list') {
            card.classList.add('list-view');
        } else {
            card.classList.remove('list-view');
        }
    });
}

function openEventModal(eventId) {
    fetch(`/events/${eventId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('event-modal').innerHTML = html;
            document.getElementById('event-modal').classList.remove('hidden');
        });
}

function closeEventModal() {
    document.getElementById('event-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('event-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEventModal();
    }
});
</script>
@endsection

