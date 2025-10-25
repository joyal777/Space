<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    // Relationships can be added later (e.g., with tasks)
    // public function tasks()
    // {
    //     return $this->hasMany(Task::class);
    // }

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
}
