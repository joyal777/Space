<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
                         // Create users first
            ProjectSeeder::class,       // Create access history fourth
            TaskSeeder::class,              // Create tasks fifth
            ProjectImageSeeder::class,      // Add images last
        ]);
    }
}
