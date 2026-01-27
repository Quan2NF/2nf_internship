<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusSeeder extends Seeder
{
    public function run()
    {
        DB::table('task_statuses')->insert([
            ['name' => 'Open', 'sort' => 1],
            ['name' => 'In Progress', 'sort' => 2],
            ['name' => 'Review', 'sort' => 3],
            ['name' => 'Done', 'sort' => 4],
            ['name' => 'Closed', 'sort' => 5],
        ]);
    }
}
