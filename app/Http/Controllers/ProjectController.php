<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        $query = Project::orderBy('created_at', 'desc');

        // Only apply filters if the inputs exist
        if ($user->role_id === 2) {
            if ($request->filled('employee')) {
                $query->where('created_by', 'like', '%' . $request->employee . '%')
                    ->orWhere('created_by', 'like', '%' . $request->employee . '%');
            }
        } else {
            $query->where('created_by', $employee->employee_id);
        }

        if ($request->filled('project_name')) {
            $query->where('project_name', 'like', '%' . $request->project_name . '%');
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', $request->end_date);
        }

        // Finally fetch results
        $projects = $query->get();

        $totalProjects         = $projects->count();
        $notStartedProjects    = $projects->where('project_status', 'not-started')->count();
        $inProgressProjects    = $projects->where('project_status', 'in-progress')->count();
        $onHoldProjects        = $projects->where('project_status', 'on-hold')->count();
        $completedProjects     = $projects->where('project_status', 'completed')->count();

        $view = $user->role_id == 2 ? 'admin.admin-project' : 'employee.employee-project';

        return view($view, compact(
            'totalProjects',
            'notStartedProjects',
            'inProgressProjects',
            'onHoldProjects',
            'completedProjects',
            'projects'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role_id = Auth::user()->role_id;
        return view('employee.createproject', compact('role_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // Create new project
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        $request->validate([
            'project_name'       => 'required|string|max:255',
            'project_desc'       => 'nullable|string',
            'created_by'         => 'required|string|max:255',
            'start_date'         => 'nullable|date',
            'end_date'           => 'nullable|date',
            'project_status'     => 'required|in:not-started,in-progress,on-hold,completed',
        ]);

        $project = new Project();
        $project->project_name    = $request->project_name;
        $project->project_desc    = $request->project_desc;
        $project->created_by      = $employee->employee_id;
        $project->start_date      = $request->start_date;
        $project->end_date        = $request->end_date;
        $project->project_status  = $request->project_status;
        
        $project->save();

        return redirect()->route('project.create')->with('success', 'Project created successfully!');
    }

    // Mark project as completed
    public function complete($id)
    {
        $project = Project::findOrFail($id);
        $project->update(['status' => 'completed']);

        return response()->json(['message' => 'Project marked as completed']);
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
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'project_status' => 'required|in:not-started,in-progress,on-hold,completed',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date',
        ]);

        $project->project_status = $request->project_status;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->save();

        return redirect()->back()->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->back()->with('success', 'Project deleted successfully.');
    }
}
