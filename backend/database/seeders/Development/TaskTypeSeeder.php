<?php

namespace Database\Seeders\Development;

use App\Models\TaskType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Task',        'sort' => 1],
            ['name' => 'Bug',         'sort' => 2],
            ['name' => 'Feature',     'sort' => 3],
            ['name' => 'Improvement', 'sort' => 4],
            ['name' => 'Research',    'sort' => 5],
        ];

        $typesToInsert = array_map(function ($type) {
            return [
                'name'       => $type['name'],
                'sort'       => $type['sort'],
                'is_active'  => true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ];
        }, $types);

        TaskType::insert($typesToInsert);
    }
}
