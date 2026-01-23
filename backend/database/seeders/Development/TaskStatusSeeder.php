<?php

namespace Database\Seeders\Development;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Open',        'sort' => 1],
            ['name' => 'In Progress', 'sort' => 2],
            ['name' => 'On Hold',     'sort' => 3],
            ['name' => 'Resolved',    'sort' => 4],
            ['name' => 'Closed',      'sort' => 5],
        ];

        $statusesToInsert = array_map(function ($status) {
            return [
                'name'       => $status['name'],
                'sort'       => $status['sort'],
                'is_active'  => true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ];
        }, $statuses);

        TaskStatus::insert($statusesToInsert);
    }
}
