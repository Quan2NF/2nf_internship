<?php

namespace Database\Seeders\Development;

use App\Models\Position;
use Illuminate\Database\Seeder;
use App\Enums\Position\PositionScope;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            [
                'code'  => 'ADMIN',
                'name'  => 'System Administrator',
                'scope' => PositionScope::System,
            ],
            [
                'code'  => 'PMO',
                'name'  => 'Project Management Officer',
                'scope' => PositionScope::System,
            ],
            [
                'code'  => 'PHP-DEV',
                'name'  => 'PHP Developer',
                'scope' => PositionScope::Project,
            ],
            [
                'code'  => 'C-SHARP-DEV',
                'name'  => 'C# Developer',
                'scope' => PositionScope::Project,
            ],
            [
                'code'  => 'HR',
                'name'  => 'Human Resource',
                'scope' => PositionScope::Project,
            ],
        ];

        $positionsToInsert = array_map(function ($position) {
            return [
                'code'       => $position['code'],
                'name'       => $position['name'],
                'scope'      => $position['scope']->value,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $positions);

        Position::insert($positionsToInsert);
    }
}
