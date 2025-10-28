<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'project_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        // Handle image upload
        if ($request->hasFile('project_image')) {
            $image = $request->file('project_image');
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
            
            // Store image in public directory
            $image->move(public_path('frontend/images/projects'), $imageName);
            $validated['project_image'] = $imageName;
        }

        Project::create($validated);

        return Redirect::route('projects.index')->with('success', 'Project created successfully!');
    }

    public function show(string $id)
    {
        $project = Project::with(['tasks' => function($query) {
            $query->orderBy('priority')->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        $statusOptions = [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'on_hold' => 'On Hold',
            'cancelled' => 'Cancelled',
        ];

        $priorityOptions = [
            1 => 'Highest',
            2 => 'High',
            3 => 'Medium',
            4 => 'Low',
            5 => 'Lowest',
        ];

        return Inertia::render('Projects/Show', [
            'project' => $project,
            'statusOptions' => $statusOptions,
            'priorityOptions' => $priorityOptions
        ]);
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

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'project_title' => 'nullable|string|max:255',
            'project_description' => 'nullable|string',
            'project_status' => 'nullable|in:pending,in_progress,completed,on_hold,cancelled',
            'project_update' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|string',
            'project_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        // Handle image upload
        if ($request->hasFile('project_image')) {
            // Delete old image if exists
            if ($project->project_image && file_exists(public_path('frontend/images/projects/' . $project->project_image))) {
                unlink(public_path('frontend/images/projects/' . $project->project_image));
            }

            $image = $request->file('project_image');
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();

            // Store image in public directory
            $image->move(public_path('frontend/images/projects'), $imageName);
            $validated['project_image'] = $imageName;
        }

        $project->update($validated);

        return Redirect::route('projects.show', $project->id)->with('success', 'Project updated successfully!');
    }

    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return Redirect::route('projects.index')->with('success', 'Project deleted successfully!');
    }
}
