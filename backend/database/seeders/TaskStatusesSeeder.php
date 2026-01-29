<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('task_statuses')->insert([
            ['name' => 'open',        'sort' => 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'todo',        'sort' => 2, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'in_progress', 'sort' => 3, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'reviewing',   'sort' => 4, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 're_open',     'sort' => 5, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'done',        'sort' => 6, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'close',       'sort' => 7, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
