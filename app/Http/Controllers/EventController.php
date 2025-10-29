<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $query = Event::query()->orderBy('event_date', 'asc');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('event_name', 'like', '%' . $request->search . '%')
                    ->orWhere('tags', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('event_status')) {
            $query->where('event_status', $request->event_status);
        }

        if ($request->filled('event_date')) {
            $query->whereDate('event_date', '=', $request->event_date);
        }

        // Finally fetch results
        $events = $query->get();

        // FOR CALENDAR
        $calendarEvents = $events->map(function ($event) {
            return [
                'title'         => $event->event_name,
                'start'         => Carbon::parse($event->event_date)->toDateString(),
                'color'         => '#71b0f8ff',
                'url'           => route('event.show', $event->id),
            ];
        });

        $categories = ['meeting', 'conference', 'workshop', 'networking', 'webinar', 'social', 'other']; 
        $eventStatuses = ['upcoming', 'ongoing', 'completed', 'cancelled']; // don’t change dynamically — they’re controlled logic states, not user input

        $view = $user->role_id == 2 ? 'admin.admin-event' : 'employee.employee-event';

        return view($view, compact('events', 'calendarEvents', 'categories', 'eventStatuses'));

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

        // ✅ 1. Validate all important columns that are NOT nullable
        $request->validate([
            'event_name'        => 'required|string|max:100',
            'description'       => 'required|string',
            'event_date'        => 'required|date',
            'event_time'        => 'required|date_format:H:i',
            'event_location'    => 'required|string|max:255',
            'category'          => 'required|in:meeting,conference,workshop,networking,webinar,social,other',
            'capacity'          => 'required|integer|min:1',
            'attendees'         => 'nullable|integer|min:0',
            'price'             => 'nullable|numeric|min:0',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // max 2MB
            'event_status'      => 'required|in:upcoming,ongoing,completed,cancelled',
            'organizer'         => 'required|string|max:100',
            'tags'              => 'nullable|array',    // expecting an array from form
            'rsvp_required'     => 'nullable|boolean',

        ]);

        // ✅ 2. Handle image upload if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
            // saves files to storage/app/public/events.
        }

        // ✅ 3. Create and save event
        $event = new Event();
        $event->created_by       = $employee->employee_id;
        $event->event_name       = $request->event_name;
        $event->description      = $request->description;
        $event->event_date       = $request->event_date;
        $event->event_time       = $request->event_time;
        $event->event_location   = $request->event_location;
        $event->category         = $request->category;
        $event->capacity         = $request->capacity;
        $event->attendees        = $request->attendees ?? 0; // default
        $event->price            = $request->price ?? 0;     // default
        $event->image            = $imagePath; // can be null
        $event->organizer        = $request->organizer;
        $event->tags             = $request->tags ? json_encode($request->tags) : null; // store as JSON or null
        $event->rsvp_required    = $request->boolean('rsvp_required', false);
        // event_status defaults to "upcoming" in the migration

        $event->save();

        return redirect()->route('event.create')->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        return view('employee.employee-eventshow', compact('event'));
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
