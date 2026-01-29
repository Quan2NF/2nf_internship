<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskTypesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('task_types')->insert([
            ['name' => 'task',  'sort' => 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'bug',   'sort' => 2, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'story', 'sort' => 3, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
