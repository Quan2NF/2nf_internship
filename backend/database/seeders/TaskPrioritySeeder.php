<?php

namespace Database\Seeders;

use App\Enums\IssuePriority;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskPrioritySeeder extends Seeder
{
    public function run(): void
    {
        foreach (IssuePriority::cases() as $index => $priority) {
            DB::table('task_priorities')->insert([
                'id' => $priority->value,
                'name' => $priority->label(),
                'sort' => $index + 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

