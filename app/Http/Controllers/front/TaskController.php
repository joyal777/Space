<?php

namespace App\Http\Controllers\front;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('project')
            ->latest()
            ->paginate(10);

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'statusOptions' => $this->getStatusOptions(),
            'priorityOptions' => $this->getPriorityOptions(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::whereIn('project_status', ['pending', 'in_progress'])
            ->get(['id', 'project_name', 'project_code']);

        return Inertia::render('Tasks/Create', [
            'projects' => $projects,
            'statusOptions' => $this->getStatusOptions(),
            'priorityOptions' => $this->getPriorityOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_name' => 'required|string|max:255',
            'task_description' => 'nullable|string',
            'task_status' => 'required|in:pending,in_progress,completed,on_hold,cancelled',
            'priority' => 'required|integer|between:1,5',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|integer|min:0',
            'actual_hours' => 'nullable|integer|min:0',
            'assigned_to' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $task = Task::create($validated);

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load('project');
        $projects = Project::latest()->get();
        return Inertia::render('Tasks/Show', [
            'projects' => $projects,
            'task' => $task,
            'statusOptions' => $this->getStatusOptions(),
            'priorityOptions' => $this->getPriorityOptions(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $projects = Project::whereIn('project_status', ['pending', 'in_progress'])
            ->get(['id', 'project_name', 'project_code']);

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'projects' => $projects,
            'statusOptions' => $this->getStatusOptions(),
            'priorityOptions' => $this->getPriorityOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_name' => 'required|string|max:255',
            'task_description' => 'nullable|string',
            'task_status' => 'required|in:pending,in_progress,completed,on_hold,cancelled',
            'priority' => 'required|integer|between:1,5',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|integer|min:0',
            'actual_hours' => 'nullable|integer|min:0',
            'assigned_to' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        // Set completed_at if status is changed to completed
        if ($validated['task_status'] === 'completed' && $task->task_status !== 'completed') {
            $validated['completed_at'] = now();
        }

        $task->update($validated);

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Get tasks for a specific project
     */
    public function projectTasks(Project $project)
    {
        $tasks = $project->tasks()
            ->latest()
            ->paginate(10);

        return Inertia::render('Tasks/ProjectTasks', [
            'project' => $project,
            'tasks' => $tasks,
            'statusOptions' => $this->getStatusOptions(),
            'priorityOptions' => $this->getPriorityOptions(),
        ]);
    }

    /**
     * Get status options for tasks
     */
    private function getStatusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'on_hold' => 'On Hold',
            'cancelled' => 'Cancelled',
        ];
    }

    /**
     * Get priority options for tasks
     */
    private function getPriorityOptions(): array
    {
        return [
            1 => 'Highest',
            2 => 'High',
            3 => 'Medium',
            4 => 'Low',
            5 => 'Lowest',
        ];
    }
}
