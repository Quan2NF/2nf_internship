<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Position;
use Illuminate\Database\Seeder;
use App\Enums\Position\PositionScope;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            return;
        }

        $user = User::updateOrCreate(
            ['employee_code' => 'EMP001'],   // lookup key ONLY
            [
            'employee_code' => 'EMP001',
            'name'          => 'Nguyen Van A',
            'email'         => 'haminhdunghl@gmail.com',
            'password'      => '123456', // auto-hashed
            'phone_number'  => '0900000000',
            'birthday'      => '1995-01-01',
            'gender'        => 1, // Male
            'join_date'     => '2024-01-01',
            'resign_date'   => null,
            'avatar'        => null,
            'is_active'     => 1,
            ]
        );

        $user2 = User::updateOrCreate(
            ['employee_code' => 'EMP002'],   // lookup key ONLY
            [
                'employee_code' => 'EMP002',
                'name'          => 'Tran Thi B',
                'email'         => 'tranthib@example.com',
                'password'      => '123456', // auto-hashed
                'phone_number'  => '0911111111',
                'birthday'      => '1998-06-15',
                'gender'        => 2, // Female
                'join_date'     => '2024-03-01',
                'resign_date'   => null,
                'avatar'        => null,
                'is_active'     => 1,
            ]
        );

        $user3 = User::updateOrCreate(
            ['employee_code' => 'EMP003'],   // lookup key ONLY
            [
                'employee_code' => 'EMP003',
                'name'          => 'Tran Thi C',
                'email'         => 'tranthic@example.com',
                'password'      => '123456', // auto-hashed
                'phone_number'  => '0922222222',
                'birthday'      => '1998-03-15',
                'gender'        => 2, // Female (assuming enum/int mapping)
                'join_date'     => '2024-06-01',
                'resign_date'   => null,
                'avatar'        => null,
                'is_active'     => 1,
            ]
        );

        $adminPosition = Position::updateOrCreate(
            ['code' => 'ADMIN'],
            [
                'name'  => 'System Administrator',
                'scope' => PositionScope::System,
            ]
        );

        $dev1 = Position::updateOrCreate(
            ['code' => 'DEV001'],
            [
                'name'  => 'PHP Developer',
                'scope' => PositionScope::Project,
            ]
        );

        $dev2 = Position::updateOrCreate(
            ['code' => 'DEV002'],
            [
                'name'  => 'C Sharp Developer',
                'scope' => PositionScope::Project,
            ]
        );

        // attach without duplication
        $user->positions()->syncWithoutDetaching([
            $adminPosition->id => [
                'start_date' => now(),
                'end_date'   => null,
            ]
        ]);

        $user2->positions()->syncWithoutDetaching([
            $dev1->id => [
                'start_date' => now(),
                'end_date'   => null,
            ],
            $dev2->id => [
                'start_date' => now(),
                'end_date'   => null,
            ]
        ]);
    }
}
