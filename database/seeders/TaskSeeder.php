<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            $this->command->info('No projects found. Please run ProjectSeeder first.');
            return;
        }

        $tasks = [];

        foreach ($projects as $project) {
            // Create 3-8 tasks for each project
            $taskCount = rand(3, 8);

            for ($i = 1; $i <= $taskCount; $i++) {
                $tasks[] = [
                    'project_id' => $project->id,
                    'task_code' => 'TASK-' . Str::upper(Str::random(8)), // Generate task_code manually
                    'task_name' => "Task $i for " . $project->project_name,
                    'task_description' => "This is task $i description for the project " . $project->project_name,
                    'task_status' => $this->getRandomStatus(),
                    'priority' => rand(1, 5),
                    'start_date' => now()->subDays(rand(1, 30)),
                    'end_date' => now()->addDays(rand(1, 60)),
                    'estimated_hours' => rand(1, 40),
                    'actual_hours' => rand(0, 50),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Chunk insert to avoid memory issues
        foreach (array_chunk($tasks, 100) as $chunk) {
            Task::insert($chunk);
        }

        $this->command->info('Tasks seeded successfully!');
    }

    private function getRandomStatus(): string
    {
        $statuses = ['pending', 'in_progress', 'completed', 'on_hold'];
        $weights = [30, 40, 20, 10]; // Probability weights

        $random = rand(1, 100);
        $cumulative = 0;

        foreach ($statuses as $index => $status) {
            $cumulative += $weights[$index];
            if ($random <= $cumulative) {
                return $status;
            }
        }

        return 'pending';
    }
}
