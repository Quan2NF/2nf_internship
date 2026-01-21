<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProjectDemoSeeder extends Seeder
{
    
    public function run(): void
    {
        DB::transaction(function () {
 
            $email = 'minhadcarry@gmail.com';
            $employeeCode = 'E0001';

            $userId = DB::table('users')->where('email', $email)->value('id');
            if (!$userId) {
                $userId = DB::table('users')->insertGetId([
                    'employee_code' => $employeeCode,
                    'name' => 'minhadcarry',
                    'email' => $email,
                    'password' => Hash::make('minhadcarry'),
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }


            $projectName = 'Demo Project';

            $projectId = DB::table('projects')
                ->where('name', $projectName)
                ->where('user_id', $userId)
                ->value('id');

            if (!$projectId) {
                $projectId = DB::table('projects')->insertGetId([
                    'name' => $projectName,
                    'description' => 'Project for policy testing',
                    'user_id' => $userId,
                    'status' => 'new',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $projectMemberId = DB::table('project_members')
                ->where('project_id', $projectId)
                ->where('user_id', $userId)
                ->value('id');

            if (!$projectMemberId) {
                $projectMemberId = DB::table('project_members')->insertGetId([
                    'project_id' => $projectId,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }


            $roleCode = 'PM';
            $roleId = DB::table('roles')->where('code', $roleCode)->value('id');
            if (!$roleId) {
                throw new \RuntimeException("Role '{$roleCode}' not found. Please run RolesSeeder first.");
            }


            DB::table('project_member_roles')->updateOrInsert(
                ['project_member_id' => $projectMemberId, 'role_id' => $roleId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        });
    }
}
