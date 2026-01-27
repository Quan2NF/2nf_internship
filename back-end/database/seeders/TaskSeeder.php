<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        DB::table('tasks')->insert([
            [
                'project_id'     => 1,
                'parent_id'      => null,
                'subject'        => 'Seed: Fix login bug',
                'description'    => 'Describe steps to reproduce and fix.',
                'status_id'      => 1,
                'type_id'        => 1,
                'priority_id'    => 1,
                'assigned_to'    => 2,
                'created_by'     => 1,
                'start_date'     => '2026-01-20',
                'due_date'       => '2026-01-25',
                'estimated_hours'=> 4.50,
                'actual_hours'   => 0.00,
                'progress_rate'  => 0,
                'is_private'     => 0,
                'closed_at'      => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'project_id'     => 1,
                'parent_id'      => null,
                'subject'        => 'Seed: Add search filter',
                'description'    => 'Implement search by subject/description.',
                'status_id'      => 1,
                'type_id'        => 2,
                'priority_id'    => 2,
                'assigned_to'    => 3,
                'created_by'     => 1,
                'start_date'     => '2026-01-22',
                'due_date'       => '2026-01-30',
                'estimated_hours'=> 8.00,
                'actual_hours'   => 1.50,
                'progress_rate'  => 20,
                'is_private'     => 0,
                'closed_at'      => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'project_id'     => 2,
                'parent_id'      => null,
                'subject'        => 'Seed: Documentation update',
                'description'    => 'Update README and API docs.',
                'status_id'      => 2,
                'type_id'        => 3,
                'priority_id'    => 3,
                'assigned_to'    => null,
                'created_by'     => 2,
                'start_date'     => null,
                'due_date'       => null,
                'estimated_hours'=> null,
                'actual_hours'   => null,
                'progress_rate'  => 0,
                'is_private'     => 0,
                'closed_at'      => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ]);
    }
}
