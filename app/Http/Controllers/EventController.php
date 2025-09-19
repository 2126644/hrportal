<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('event_status') && $request->event_status !== 'all') {
            $query->where('event_status', $request->event_status);
        }

        if ($request->filled('price_filter')) {
            if ($request->price_filter === 'free') {
                $query->where('price', 0);
            } elseif ($request->price_filter === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        $events = $query->orderBy('event_date', 'asc')->get();
        
        // Calculate stats
        $stats = [
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('event_status', 'upcoming')->count(),
            'total_attendees' => Event::sum('attendees'),
            'average_attendance' => Event::count() > 0 ? round(Event::sum('attendees') / Event::count()) : 0
        ];

        $viewMode = $request->get('view', 'grid');

        return view('employee.event', compact('events', 'stats', 'viewMode'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.newevent');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        $request->validate([
            'event_name'        => 'required|string|max:100',
            'event_date'        => 'required|date',
            'event_time'        => 'required|date_format:H:i',
            'event_location'    => 'required|string|max:255',
        ]);

        $event = new Event();
        $event->event_name       = $request->event_name;
        $event->event_date       = $request->event_date;
        $event->event_time       = $request->event_time;
        $event->event_location   = $request->event_location;

        $event->save();

        return redirect()->route('event.create')->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
