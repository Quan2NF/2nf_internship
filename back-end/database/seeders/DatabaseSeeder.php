<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi UserSeeder để tạo admin test
        $this->call([
            UserSeeder::class,
        ]);
    }
}
