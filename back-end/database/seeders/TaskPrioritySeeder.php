<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskPrioritySeeder extends Seeder
{
    public function run()
    {
        DB::table('task_priorities')->insert([
            ['name' => 'Low', 'sort' => 1],
            ['name' => 'Medium', 'sort' => 2],
            ['name' => 'High', 'sort' => 3],
            ['name' => 'Critical', 'sort' => 4],
        ]);
    }
}
