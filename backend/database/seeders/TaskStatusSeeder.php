<?php

namespace Database\Seeders;

use App\Enums\IssueStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusSeeder extends Seeder
{
    public function run(): void
    {
        foreach (IssueStatus::cases() as $index => $status) {
            DB::table('task_statuses')->insert([
                'id' => $status->value,
                'name' => $status->label(),
                'sort' => $index + 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

