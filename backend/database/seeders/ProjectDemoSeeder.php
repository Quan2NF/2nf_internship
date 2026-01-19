<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProjectDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1) create a user
        $userId = DB::table('users')->insertGetId([
            'employee_code' => 'E0001',
            'name' => 'Owner User',
            'email' => 'minhadcarry@gmail.com',
            'password' => Hash::make('minhadcarry'),
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2) create a project (owner)
        $projectId = DB::table('projects')->insertGetId([
            'name' => 'Demo Project',
            'description' => 'Project for policy testing',
            'user_id' => $userId,
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3) add member record (owner is also member)
        $projectMemberId = DB::table('project_members')->insertGetId([
            'project_id' => $projectId,
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4) attach role PM (or ADMIN) to that member
        $roleId = DB::table('roles')->where('code', 'PM')->value('id');

        DB::table('project_member_roles')->insert([
            'project_member_id' => $projectMemberId,
            'role_id' => $roleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
