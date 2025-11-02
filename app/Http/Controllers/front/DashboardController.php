<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUserAccess;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get recently accessed projects (last 5)
        $recentProjects = Project::whereHas('userAccesses', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['userAccesses' => function ($query) use ($user) {
            $query->where('user_id', $user->id)->orderBy('accessed_at', 'desc');
        }])
        ->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('status', 'accepted');
        })
        ->withCount(['tasks as completed_tasks_count' => function ($query) {
            $query->where('task_status', 'completed');
        }])
        ->withCount('tasks as total_tasks_count')
        ->orderByDesc(
            ProjectUserAccess::select('accessed_at')
                ->whereColumn('project_id', 'projects.id')
                ->where('user_id', $user->id)
                ->latest('accessed_at')
                ->limit(1)
        )
        ->limit(5)
        ->get()
        ->map(function ($project) {
            return [
                'id' => $project->id,
                'project_code' => $project->project_code,
                'project_name' => $project->project_name,
                'project_title' => $project->project_title,
                'project_status' => $project->project_status,
                'project_image' => $project->project_image,
                'last_accessed' => $project->userAccesses->first()->accessed_at ?? null,
                'progress_percentage' => $project->total_tasks_count > 0
                    ? ($project->completed_tasks_count / $project->total_tasks_count) * 100
                    : 0,
                'status_color' => $project->getStatusColorAttribute(),
            ];
        });

        // Get user's projects count and other stats
        $userProjectsCount = $user->teamProjects()->count();
        $activeProjectsCount = $user->teamProjects()->where('project_status', 'in_progress')->count();

        return Inertia::render('Dashboard', [
            'recentProjects' => $recentProjects,
            'stats' => [
                'total_projects' => $userProjectsCount,
                'active_projects' => $activeProjectsCount,
                'recent_count' => $recentProjects->count(),
            ]
        ]);
    }
}
