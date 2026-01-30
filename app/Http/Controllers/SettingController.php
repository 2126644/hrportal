<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.admin-setting', [
            'leaveTypes'      => setting('leave_types', []),
            'eventCategories' => setting('event_categories', []),
        ]);
    }

//     public function index()
// {
//     return view('admin.settings', [
//         'settings' => [
//             'max_timeslip_hours'  => setting('max_timeslip_hours', 3),
//         ],
//     ]);
// }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request)
    {
        $data = $request->validate([
            'leave_types'       => 'array',
            'leave_types.*'     => 'string|max:50',

            'event_categories'  => 'array',
            'event_categories.*' => 'string|max:50',
        ]);

        Setting::updateOrCreate(
            ['key' => 'leave_types'],
            ['value' => json_encode(array_values($data['leave_types'] ?? []))]
        );

        Setting::updateOrCreate(
            ['key' => 'event_categories'],
            ['value' => json_encode(array_values($data['event_categories'] ?? []))]
        );

        return back()->with('success', 'Settings updated successfully.');
    }

//     public function update(Request $request)
// {
//     $data = $request->validate([
//         'max_timeslip_hours'  => 'nullable|integer|min:1',
//     ]);

//     foreach ([
//         'max_timeslip_hours',
//     ] as $key) {
//         if (array_key_exists($key, $data)) {
//             Setting::updateOrCreate(
//                 ['key' => $key],
//                 ['value' => $data[$key]]
//             );
//         }
//     }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
