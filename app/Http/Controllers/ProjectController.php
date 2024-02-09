<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'Developer') {
            $projects = Project::where('lead_developer_id', $user->id)
                ->orWhereHas('developers', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->get();
        } else {
            $projects = Project::all();
        }
        $teamMembers = User::where('role', '!=', 'Admin')->get();
        return view('dashboard', compact('projects', 'teamMembers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $developers = User::where('role', 'Developer')->get();
        $managers = User::where('role','Manager')->get();

        return view('projects.create', compact('developers', 'managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'project_name' => 'required',
            'bu_name' => 'required',
            'start_date' => 'required|date',
            'duration' => 'required|numeric',
            'end_date' => 'required|date',
            'lead_developer_id' => 'required|exists:users,id,role,Developer',
            'other_developers' => 'array|exists:users,id,role,Developer',
            'last_report' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $project = Project::create($validatedData);

        $project->pic_id = $request->input('pic_id');
        $project->lead_developer_id = $request->input('lead_developer_id');

        if ($request->has('other_developers')) {
            $project->developers()->attach($request->input('other_developers'));
        }

        return redirect()->route('dashboard')->with('success', 'Project created successfully');
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);

        $developers = User::where('role', 'Developer')->get();
        $managers = User::where('role','Manager')->get();

        return view('projects.edit', compact('project', 'developers', 'managers'));

        if (auth()->user()->isManager()) {
            return view('projects.edit', compact('project'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'project_name' => 'required',
            'bu_name' => 'required',
            'start_date' => 'required|date',
            'duration' => 'required|numeric',
            'end_date' => 'required|date',
            'lead_developer_id' => 'required|exists:users,id,role,Developer',
            'other_developers' => 'array|exists:users,id,role,Developer',
        ]);
    
        // Update the project
        $project = Project::findOrFail($id);
        $project->update($validatedData);
    
        $project->lead_developer_id = $request->input('lead_developer_id');
        $project->developers()->sync($request->input('other_developers', []));

        return redirect()->route('dashboard')->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
    
        return redirect()->route('dashboard')->with('success', 'Project deleted successfully');
    }

    public function showUpdateProgressForm($id)
    {
        $project = Project::findOrFail($id);
    
        if (auth()->user()->id !== $project->lead_developer_id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to edit progress for this project.');
        }
    
        return view('projects.update-progress', compact('project'));
    }

    public function updateProgress(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Validate and update only the allowed fields
        $validatedData = $request->validate([
            'end_date' => 'nullable|date',
            'development_methodology' => 'required|in:Waterfall,Agile,Scrum,Kanban,DevOps',
            'system_platform' => 'required|in:Web-based,Mobile,Stand-alone',
            'deployment_type' => 'required|in:Cloud,On-premises',
            'status' => 'required|in:Ahead of Schedule,On Schedule,Delayed,Completed',
            'last_report' => 'nullable|date',
            'description' => 'required|string',
        ]);

        // Update the project with the validated data
        $project->update($validatedData);

        return redirect()->route('dashboard')->with('success', 'Project progress updated successfully');
    }
}
