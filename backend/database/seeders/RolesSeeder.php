<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->upsert([
            ['code' => 'ADMIN',  'name' => 'Admin'],
            ['code' => 'PMO',    'name' => 'PMO'],
            ['code' => 'PM',     'name' => 'Project Manager'],
            ['code' => 'DEV_FE', 'name' => 'Developer FrontEnd'],
            ['code' => 'DEV_BE', 'name' => 'Developer BackEnd'],
            ['code' => 'QA',     'name' => 'QA'],
            ['code' => 'TEST',   'name' => 'Tester'],
            ['code' => 'COMTOR', 'name' => 'Comtor'],
        ], ['code'], ['name']);
    }
}
