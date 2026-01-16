<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed users for testing
     */
    public function run(): void
    {
        // Tạo Position Admin
        $adminPosition = Position::firstOrCreate(
            ['name' => 'Admin'],
            ['is_admin' => true]
        );

        // Tạo Position Staff (không phải admin)
        $staffPosition = Position::firstOrCreate(
            ['name' => 'Staff'],
            ['is_admin' => false]
        );

        // Tạo Admin User để test
        $admin = User::create([
            'name' => 'Nguyen Van A',
            'email' => 'admin@company.com',
            'password' => Hash::make('password123'), // Password: password123
            'is_active' => true,
            'avatar' => 'http://domain.com/avatar.jpg',
        ]);

        // Gán position admin cho user
        $admin->positions()->attach($adminPosition->id);

       

        // Tạo Staff User để test
        $staff = User::create([
            'name' => 'Nguyen Van B',
            'email' => 'staff@company.com',
            'password' => Hash::make('password123'), // Password: password123
            'is_active' => true,
            'avatar' => null,
        ]);

        // Gán position staff cho user
        $staff->positions()->attach($staffPosition->id);


        // Tạo thêm inactive user để test case login fail
        $inactiveUser = User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@company.com',
            'password' => Hash::make('password123'),
            'is_active' => false, // Inactive
            'avatar' => null,
        ]);

        $inactiveUser->positions()->attach($staffPosition->id);

       
    }
}
