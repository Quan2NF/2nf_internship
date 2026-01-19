<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->insert([
            ['code' => 'ADMIN', 'name' => 'Admin'],
            ['code' => 'PM',    'name' => 'Project Manager'],
            ['code' => 'DEV',   'name' => 'Developer'],
            ['code' => 'QA',    'name' => 'QA'],
            ['code' => 'TEST',  'name' => 'Tester'],
        ]);
    }
}
