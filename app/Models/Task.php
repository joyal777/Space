<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'task_code',
        'task_name',
        'task_description',
        'task_status',
        'priority',
        'start_date',
        'end_date',
        'completed_at',
        'estimated_hours',
        'actual_hours',
        'assigned_to',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'completed_at' => 'date',
        'assigned_to' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (empty($task->task_code)) {
                $task->task_code = 'TASK-' . Str::upper(Str::random(8));
            }
        });
    }

    // Relationship with Project
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Helper methods
    public function isOverdue(): bool
    {
        return $this->end_date && $this->end_date->isPast() && !$this->isCompleted();
    }

    public function isCompleted(): bool
    {
        return $this->task_status === 'completed';
    }

    public function isInProgress(): bool
    {
        return $this->task_status === 'in_progress';
    }

    public function getProgressPercentage(): float
    {
        if ($this->isCompleted()) return 100;
        if ($this->estimated_hours && $this->actual_hours) {
            return min(($this->actual_hours / $this->estimated_hours) * 100, 100);
        }
        return 0;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->task_status) {
            'pending' => 'gray',
            'in_progress' => 'blue',
            'completed' => 'green',
            'on_hold' => 'yellow',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            1 => 'red',    // Highest
            2 => 'orange', // High
            3 => 'yellow', // Medium
            4 => 'blue',   // Low
            5 => 'gray',   // Lowest
            default => 'gray',
        };
    }

    public function getPriorityTextAttribute(): string
    {
        return match($this->priority) {
            1 => 'Highest',
            2 => 'High',
            3 => 'Medium',
            4 => 'Low',
            5 => 'Lowest',
            default => 'Medium',
        };
    }
}
