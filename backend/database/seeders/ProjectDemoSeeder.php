<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectDemoSeeder extends Seeder
{
    /**
     * Create 1 demo project and link owner into project_members + project_member_roles.
     *
     * Requirements:
     * - users table exists
     * - roles table has at least 'PM' role code
     * - project_members + project_member_roles tables exist
     */
    public function run(): void
    {
        DB::transaction(function () {

            $ownerEmail = 'minh955371@gmail.com';
            $ownerId = (int) DB::table('users')->where('email', $ownerEmail)->value('id');

            if (!$ownerId) {
                throw new \RuntimeException("Owner user not found: {$ownerEmail}. Please run AdminDemoSeeder first.");
            }

            // 2) create project
            $projectCode = 'PJT-001';

            $projectId = (int) DB::table('projects')->where('code', $projectCode)->value('id');
            if (!$projectId) {
                $projectId = DB::table('projects')->insertGetId([
                    'code' => $projectCode,
                    'name' => 'Demo Project',
                    'description' => 'Project for policy testing',
                    'status' => 'new',

                    'planned_start_date' => null,
                    'planned_end_date' => null,
                    'start_date' => null,
                    'end_date' => null,

                    'progress_rate' => 0,
                    'is_public' => 0,
                    'is_active' => 1,

                    'created_by' => $ownerId,
                    'updated_by' => $ownerId,

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            
            $projectMemberId = (int) DB::table('project_members')
                ->where('project_id', $projectId)
                ->where('user_id', $ownerId)
                ->value('id');

            if (!$projectMemberId) {
                $projectMemberId = DB::table('project_members')->insertGetId([
                    'project_id' => $projectId,
                    'user_id' => $ownerId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 4) assign PM role to owner in this project (project_member_roles)
            $pmRoleId = (int) DB::table('roles')->where('code', 'PM')->value('id');
            if (!$pmRoleId) {
                throw new \RuntimeException("Role 'PM' not found. Please run RolesSeeder first.");
            }

            DB::table('project_member_roles')->updateOrInsert(
                ['project_member_id' => $projectMemberId, 'role_id' => $pmRoleId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        });
    }
}
