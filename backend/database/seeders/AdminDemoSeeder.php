<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminDemoSeeder extends Seeder
{
    
    public function run(): void
    {
        DB::transaction(function () {
            $email = 'minh955371@gmail.com';
            $employeeCode = 'A0001';

            $adminId = DB::table('users')->where('email', $email)->value('id');

            if (!$adminId) {
                $adminId = DB::table('users')->insertGetId([
                    'employee_code' => $employeeCode,
                    'name' => 'System Admin',
                    'email' => $email,
                    'password' => Hash::make('minh955371'),
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            
            if (!Schema::hasTable('user_system_roles')) {
                return;
            }

            $adminRoleId = DB::table('roles')->where('code', 'ADMIN')->value('id');
            if (!$adminRoleId) {
                throw new \RuntimeException("Role 'ADMIN' not found. Please run RolesSeeder first.");
            }

            
            DB::table('user_system_roles')->updateOrInsert(
                ['user_id' => $adminId, 'role_id' => $adminRoleId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        });
    }
}
