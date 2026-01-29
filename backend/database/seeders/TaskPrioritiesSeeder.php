<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskPrioritiesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('task_priorities')->insert([
            ['name' => 'low',    'sort' => 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'medium', 'sort' => 2, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'high',   'sort' => 3, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'urgent', 'sort' => 4, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
