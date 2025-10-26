<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'project_code',
        'project_name',
        'project_title',
        'project_description',
        'project_status',
        'project_update',
        'project_image',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->project_code)) {
                $project->project_code = 'PROJ-' . Str::upper(Str::random(8));
            }
        });
    }

    // Relationship with Tasks
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // Helper methods
    public function isActive()
    {
        return $this->project_status === 'in_progress';
    }

    public function isCompleted()
    {
        return $this->project_status === 'completed';
    }

    public function getStatusColorAttribute()
    {
        return match($this->project_status) {
            'pending' => 'gray',
            'in_progress' => 'blue',
            'completed' => 'green',
            'on_hold' => 'yellow',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    // New helper methods for tasks
    public function getCompletedTasksCount(): int
    {
        return $this->tasks()->where('task_status', 'completed')->count();
    }

    public function getTotalTasksCount(): int
    {
        return $this->tasks()->count();
    }

    public function getProgressPercentage(): float
    {
        $total = $this->getTotalTasksCount();
        if ($total === 0) return 0;

        return ($this->getCompletedTasksCount() / $total) * 100;
    }
}
