<?php

namespace Database\Seeders\Development;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->admin()
            ->create([
                'employee_code'  =>'EMP-0001',
                'name'  => 'System Admin',
                'email' => 'haminhdunghl@gmail.com',
            ]);

        User::factory(20)
            ->user()
            ->create();
    }
}
