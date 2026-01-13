<?php

namespace Database\Seeders;

use App\Enums\IssueType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskTypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (IssueType::cases() as $index => $type) {
            DB::table('task_types')->insert([
                'id' => $type->value,
                'name' => $type->label(),
                'sort' => $index + 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

