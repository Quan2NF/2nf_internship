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

        $adminPosition = Position::updateOrCreate(
            ['code' => 'ADMIN'],
            [
                'name'  => 'System Administrator',
                'scope' => PositionScope::System,
            ]
        );

        // attach without duplication
        $user->positions()->syncWithoutDetaching([
            $adminPosition->id => [
                'start_date' => now(),
                'end_date'   => null,
            ]
        ]);
    }
}
