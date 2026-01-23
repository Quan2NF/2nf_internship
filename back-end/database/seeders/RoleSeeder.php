<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['code' => 'ADMIN', 'name' => 'Administrator'],
            ['code' => 'PM', 'name' => 'Project Manager'],
            ['code' => 'BA', 'name' => 'Business Analyst'],
            ['code' => 'DEV', 'name' => 'Developer'],
            ['code' => 'QA', 'name' => 'Quality Assurance'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['code' => $role['code']],
                ['name' => $role['name']]
            );
        }
    }
}
