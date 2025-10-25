<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
            'statusOptions' => [
                'pending' => 'Pending',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'on_hold' => 'On Hold',
                'cancelled' => 'Cancelled'
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('Projects/Create', [
            'statusOptions' => [
                'pending' => 'Pending',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'on_hold' => 'On Hold',
                'cancelled' => 'Cancelled'
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'project_title' => 'nullable|string|max:255',
            'project_description' => 'nullable|string',
            'project_status' => 'nullable|in:pending,in_progress,completed,on_hold,cancelled',
            'project_update' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|string',
            'project_image' => 'nullable|string',
        ]);

        Project::create($validated);

        return Redirect::route('projects.index')->with('success', 'Project created successfully!');
    }

    public function show(string $id)
    {
        $project = Project::findOrFail($id);

        return Inertia::render('Projects/Show', ['project' => $project]);
    }

    public function edit(string $id)
    {
        $project = Project::findOrFail($id);

        return Inertia::render('Projects/Edit', [
            'project' => $project,
            'statusOptions' => [
                'pending' => 'Pending',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'on_hold' => 'On Hold',
                'cancelled' => 'Cancelled'
            ]
        ]);
    }

    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'project_title' => 'nullable|string|max:255',
            'project_description' => 'nullable|string',
            'project_status' => 'required|in:pending,in_progress,completed,on_hold,cancelled',
            'project_update' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|string',
            'project_image' => 'nullable|string',
        ]);

        $project->update($validated);

        return Redirect::route('projects.index')->with('success', 'Project updated successfully!');
    }

    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return Redirect::route('projects.index')->with('success', 'Project deleted successfully!');
    }
}
