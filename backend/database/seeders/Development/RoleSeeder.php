<?php

namespace Database\Seeders\Development;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'code'  => 'PM',
                'name'  => 'Project Manager',
            ],
            [
                'code'  => 'DEV',
                'name'  => 'Developer',
            ],
            [
                'code'  => 'TESTER',
                'name'  => 'Tester',
            ],
        ];

        $rolesToInsert = array_map(function ($role) {
            return [
                'code'       => $role['code'],
                'name'       => $role['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $roles);

        Role::insert($rolesToInsert);
    }
}
