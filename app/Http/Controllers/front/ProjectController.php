<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
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

        // Create project
        $project = Project::create($validated);

        // Get authenticated user ID - try different methods
        $userId = auth()->id() ?? auth()->user()->id ?? $request->user()->id;

        // Attach the authenticated user as owner with accepted status
        $project->users()->attach($userId, [
            'role' => 'owner',
            'status' => 'accepted'
        ]);

        return Redirect::route('projects.index')->with('success', 'Project created successfully!');
    }

    public function show(string $id)
    {
        $project = Project::with(['tasks' => function($query) {
        $query->orderBy('priority')->orderBy('created_at', 'desc');
    }, 'users'])->findOrFail($id);

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

    public function inviteUser(Request $request, Project $project)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User with this email not found.');
        }

        // Check if user is already invited or is a member
        if ($project->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already invited or is a member of this project.');
        }

        // Attach user to project with pending status
        $project->users()->attach($user->id, [
            'role' => 'member',
            'status' => 'pending'
        ]);

        return back()->with('success', 'User invited successfully!');
    }
    // Accept invitation
    public function acceptInvitation(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Check if the current user is the one being invited
        $currentUserId = auth()->id();
        if ($currentUserId != $request->user_id) {
            return back()->with('error', 'You can only accept your own invitations.');
        }

        // Check if invitation exists and is pending
        $invitation = $project->users()->where('user_id', $request->user_id)->first();

        if (!$invitation) {
            return back()->with('error', 'Invitation not found.');
        }

        if ($invitation->pivot->status !== 'pending') {
            return back()->with('error', 'This invitation has already been processed.');
        }

        // Update the invitation status to accepted
        $project->users()->updateExistingPivot($request->user_id, [
            'status' => 'accepted'
        ]);

        return back()->with('success', 'Invitation accepted successfully! You are now a member of this project.');
    }

    // Reject invitation
    public function rejectInvitation(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Check if the current user is the one being invited
        $currentUserId = auth()->id();
        if ($currentUserId != $request->user_id) {
            return back()->with('error', 'You can only reject your own invitations.');
        }

        // Check if invitation exists and is pending
        $invitation = $project->users()->where('user_id', $request->user_id)->first();

        if (!$invitation) {
            return back()->with('error', 'Invitation not found.');
        }

        if ($invitation->pivot->status !== 'pending') {
            return back()->with('error', 'This invitation has already been processed.');
        }

        // Remove the user from the project (delete the invitation)
        $project->users()->detach($request->user_id);

        return back()->with('success', 'Invitation rejected successfully.');
    }


    // Remove user from project
    public function removeUser(Project $project, User $user)
    {
        $project->users()->detach($user->id);

        return back()->with('success', 'User removed from project successfully!');
    }

    public function myProjects()
    {
        $user = auth()->user();

        // Get projects where user is a member (accepted invitations)
        $projects = $user->projects()
            ->wherePivot('status', 'accepted')
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function($query) {
                $query->where('task_status', 'completed');
            }])
            ->with(['users' => function($query) {
                $query->where('status', 'accepted');
            }])
            ->latest()
            ->get();

        // Get pending invitations count
        $pendingInvitationsCount = $user->projects()
            ->wherePivot('status', 'pending')
            ->count();

        return Inertia::render('Projects/MyProjects', [
            'projects' => $projects,
            'pendingInvitationsCount' => $pendingInvitationsCount,
            'statusOptions' => [
                'pending' => 'Pending',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'on_hold' => 'On Hold',
                'cancelled' => 'Cancelled'
            ]
        ]);
    }

    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return Redirect::route('projects.index')->with('success', 'Project deleted successfully!');
    }
}
