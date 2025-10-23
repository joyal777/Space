<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $projects = [
            [
                'project_name' => 'Website Redesign',
                'project_title' => 'Company Website Redesign 2024',
                'project_code' => 'PROJ-WEBSITE24',
                'project_description' => 'Complete redesign of the company website with modern UI/UX and improved performance.',
                'project_status' => 'in_progress',
                'project_update' => 'Homepage design completed. Working on about page.',
                'start_date' => '2024-01-15',
                'end_date' => '2024-03-30',
            ],
            [
                'project_name' => 'Mobile App Development',
                'project_title' => 'Customer Mobile Application',
                'project_code' => 'PROJ-MOBILEAPP',
                'project_description' => 'Development of a cross-platform mobile application for customer engagement.',
                'project_status' => 'pending',
                'project_update' => 'Requirements gathering phase. Waiting for client approval.',
                'start_date' => '2024-02-01',
                'end_date' => '2024-06-15',
            ],
            [
                'project_name' => 'E-commerce Platform',
                'project_title' => 'Online Store Development',
                'project_code' => 'PROJ-ECOMM',
                'project_description' => 'Build a complete e-commerce platform with payment integration and inventory management.',
                'project_status' => 'completed',
                'project_update' => 'Project completed and deployed to production. Client training done.',
                'start_date' => '2023-11-01',
                'end_date' => '2024-01-20',
            ],
            [
                'project_name' => 'CRM System',
                'project_title' => 'Customer Relationship Management',
                'project_code' => 'PROJ-CRMSYS',
                'project_description' => 'Custom CRM system for sales team with lead tracking and reporting features.',
                'project_status' => 'in_progress',
                'project_update' => 'Database design completed. Starting frontend development.',
                'start_date' => '2024-01-10',
                'end_date' => '2024-04-30',
            ],
            [
                'project_name' => 'API Integration',
                'project_title' => 'Third-party API Integration Project',
                'project_code' => 'PROJ-APIINT',
                'project_description' => 'Integrate multiple third-party APIs for data synchronization and automation.',
                'project_status' => 'on_hold',
                'project_update' => 'Waiting for API documentation from third-party vendors.',
                'start_date' => '2024-01-20',
                'end_date' => 'TBD - Waiting for vendor response',
            ],
            [
                'project_name' => 'Security Audit',
                'project_title' => 'System Security Assessment',
                'project_code' => 'PROJ-SECAUDIT',
                'project_description' => 'Comprehensive security audit and vulnerability assessment of all systems.',
                'project_status' => 'pending',
                'project_update' => 'Scheduled for next quarter. Preparing audit checklist.',
                'start_date' => '2024-03-01',
                'end_date' => '2024-03-15',
            ],
            [
                'project_name' => 'Data Migration',
                'project_title' => 'Legacy System Data Migration',
                'project_code' => 'PROJ-DATAMIG',
                'project_description' => 'Migrate data from legacy systems to new cloud-based platform.',
                'project_status' => 'cancelled',
                'project_update' => 'Project cancelled due to budget constraints.',
                'start_date' => '2024-01-05',
                'end_date' => 'N/A',
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        $this->command->info('✅ 7 sample projects created successfully!');
    }
}
