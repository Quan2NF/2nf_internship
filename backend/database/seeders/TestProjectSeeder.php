<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use App\Enums\Project\ProjectStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Project 1
        Project::updateOrCreate(
            ['code' => 'PRJ-001'], // lookup key ONLY
            [
                'code' => 'PRJ-001',
                'name' => 'Internal Management System',
                'description' => 'Build an internal management platform for company operations.',
                'status' => ProjectStatus::PLANNED,
                'planned_start_date' => '2026-02-01',
                'planned_end_date' => '2026-06-30',
                'start_date' => null,
                'end_date' => null,
                'progress_rate' => 0,
                'is_public' => 0,
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        // Project 2
        Project::updateOrCreate(
            ['code' => 'PRJ-002'], // lookup key ONLY
            [
                'code' => 'PRJ-002',
                'name' => 'Website Redesign',
                'description' => 'Redesign the corporate website with a new UI/UX.',
                'status' => ProjectStatus::ACTIVE,
                'planned_start_date' => '2026-01-15',
                'planned_end_date' => '2026-03-31',
                'start_date' => '2026-01-20',
                'end_date' => null,
                'progress_rate' => 45,
                'is_public' => 1,
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 2,
            ]
        );

        // Project 3
        Project::updateOrCreate(
            ['code' => 'PRJ-003'], // lookup key ONLY
            [
                'code' => 'PRJ-003',
                'name' => 'Mobile App MVP',
                'description' => null,
                'status' => ProjectStatus::COMPLETED,
                'planned_start_date' => '2025-09-01',
                'planned_end_date' => '2025-12-31',
                'start_date' => '2025-09-05',
                'end_date' => '2025-12-20',
                'progress_rate' => 100,
                'is_public' => 0,
                'is_active' => 0,
                'created_by' => 2,
                'updated_by' => 2,
            ]
        );
    }
}
