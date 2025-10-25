<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectImageSeeder extends Seeder
{
    public function run()
    {
        $projects = Project::all();

        $images = [
            'photo-1.webp',
            'photo-2.webp',
            'photo-3.webp',
            'photo-4.webp',
            'photo-5.webp',
            'photo-6.webp',
            'photo-7.webp',
            'photo-8.png',

        ];

        foreach ($projects as $index => $project) {
            $project->update([
                'project_image' => $images[$index % count($images)],
            ]);
        }

        $this->command->info('✅ Project images updated successfully!');
    }
}
