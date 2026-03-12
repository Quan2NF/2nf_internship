<?php

namespace Database\Seeders\Development;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory()
            ->count(20)
            ->create();
    }
}
