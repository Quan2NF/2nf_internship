<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'employee_code' => 'EMP001',
            'name'          => 'Nguyen Van A',
            'email'         => 'test@demo.com',
            'password'      => '123456', // auto-hashed
            'phone_number'  => '0900000000',
            'birthday'      => '1995-01-01',
            'gender'        => 1, // Male
            'join_date'     => '2024-01-01',
            'resign_date'   => null,
            'avatar'        => null,
            'is_active'     => 1,
        ]);
    }
}
