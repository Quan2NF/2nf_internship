<?php

namespace Database\Seeders\Development;

use App\Models\TaskPriority;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [
            ['name' => 'Low',    'sort' => 1],
            ['name' => 'Medium', 'sort' => 2],
            ['name' => 'High',   'sort' => 3],
            ['name' => 'Urgent', 'sort' => 4],
        ];

        $prioritiesToInsert = array_map(function ($priority) {
            return [
                'name'       => $priority['name'],
                'sort'       => $priority['sort'],
                'is_active'  => true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ];
        }, $priorities);

        TaskPriority::insert($prioritiesToInsert);
    }
}
