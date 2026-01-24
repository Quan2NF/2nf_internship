<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $projects = [
            [
                'code' => 'PRJ-001',
                'name' => 'Alpha Project',
                'description' => 'Initial alpha project for onboarding',
                'status' => 'active',
                'planned_start_date' => $now->toDateString(),
                'planned_end_date' => $now->addDays(90)->toDateString(),
                'progress_rate' => 0,
                'is_public' => 0,
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'PRJ-002',
                'name' => 'Beta Integration',
                'description' => 'Integrate external services',
                'status' => 'planning',
                'planned_start_date' => $now->addDays(7)->toDateString(),
                'planned_end_date' => $now->addDays(97)->toDateString(),
                'progress_rate' => 0,
                'is_public' => 0,
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($projects as $p) {
            DB::table('projects')->updateOrInsert([
                'code' => $p['code']
            ], $p);
        }
    }
}
