<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['code' => 'ADMIN', 'name' => 'Admin', 'is_admin' => true],
            ['code' => 'PMO', 'name' => 'PMO', 'is_admin' => false],
            ['code' => 'PM', 'name' => 'PM', 'is_admin' => false],

            ['code' => 'DEV_BE_PHP', 'name' => 'Dev Backend (PHP)', 'is_admin' => false],
            ['code' => 'DEV_BE_JAVA', 'name' => 'Dev Backend (Java)', 'is_admin' => false],
            ['code' => 'DEV_BE_PYTHON', 'name' => 'Dev Backend (Python)', 'is_admin' => false],
            ['code' => 'DEV_BE_DOTNET', 'name' => 'Dev Backend (.Net)', 'is_admin' => false],

            ['code' => 'DEV_FE_FLUTTER', 'name' => 'Dev Frontend (Flutter)', 'is_admin' => false],
            ['code' => 'DEV_FE_ANDROID', 'name' => 'Dev Frontend (Android)', 'is_admin' => false],
            ['code' => 'DEV_FE_IOS', 'name' => 'Dev Frontend (IOS)', 'is_admin' => false],
            ['code' => 'DEV_FE_VUE', 'name' => 'Dev Frontend (VueJS)', 'is_admin' => false],
            ['code' => 'DEV_FE_REACT', 'name' => 'Dev Frontend (ReactJS)', 'is_admin' => false],
            ['code' => 'DEV_FE_ANGULAR', 'name' => 'Dev Frontend (Angular)', 'is_admin' => false],

            ['code' => 'TESTER', 'name' => 'Tester', 'is_admin' => false],
            ['code' => 'COMTOR', 'name' => 'Comtor', 'is_admin' => false],
            ['code' => 'BA', 'name' => 'BA', 'is_admin' => false],
            ['code' => 'QA', 'name' => 'QA', 'is_admin' => false],
        ];

        foreach ($positions as $position) {
            Position::updateOrCreate(
                ['code' => $position['code']],
                ['name' => $position['name'], 'is_admin' => $position['is_admin']]
            );
        }
    }
}
