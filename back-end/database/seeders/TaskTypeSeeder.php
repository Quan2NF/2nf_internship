<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('task_types')->insert([
            ['name' => 'Bug', 'sort' => 1],
            ['name' => 'Feature', 'sort' => 2],
            ['name' => 'Improvement', 'sort' => 3],
            ['name' => 'Task', 'sort' => 4],
        ]);
    }
}
