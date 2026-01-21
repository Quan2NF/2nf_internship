<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment(['local', 'testing'])) {
            $this->call([
                Development\PositionSeeder::class,
                Development\RoleSeeder::class,
                Development\UserSeeder::class,
                Development\ProjectSeeder::class,
            ]);
        }
    }
}
