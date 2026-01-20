<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private function nextEmployeeCode(): string
    {
        $row = DB::selectOne(
            "SELECT MAX(CAST(SUBSTRING(employee_code, 4) AS UNSIGNED)) AS max_num FROM users WHERE employee_code REGEXP '^EMP[0-9]{6}$'"
        );

        $max = (int) ($row?->max_num ?? 0);
        $next = $max + 1;

        return 'EMP'.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }

    private function ensureEmployeeCode(User $user): void
    {
        // Keep existing code if present and unique for this user
        if (!empty($user->employee_code)) {
            $collision = User::where('employee_code', $user->employee_code)
                ->when($user->exists, fn ($q) => $q->where('id', '!=', $user->id))
                ->exists();

            if (!$collision) {
                return;
            }
        }

        // Otherwise, generate a new unique EMP###### code
        do {
            $code = $this->nextEmployeeCode();
        } while (User::where('employee_code', $code)->exists());

        $user->employee_code = $code;
    }

    /**
     * Seed users for testing
     */
    public function run(): void
    {
        $adminPosition = Position::where('code', 'ADMIN')->first();
        $staffPosition = Position::where('code', 'DEV_BE_PHP')->first();

        if (!$adminPosition) {
            $adminPosition = Position::create(['code' => 'ADMIN', 'name' => 'Admin', 'is_admin' => true]);
        }
        if (!$staffPosition) {
            $staffPosition = Position::create(['code' => 'DEV_BE_PHP', 'name' => 'Dev Backend (PHP)', 'is_admin' => false]);
        }

        // Admin user
        $admin = User::firstOrNew(['email' => 'admin@company.com']);
        $admin->name = 'Nguyen Van A';
        $admin->password = Hash::make('password123'); // Password: password123
        $admin->phone_number = '0900000001';
        $admin->join_date = $admin->join_date ?: now()->toDateString();
        $admin->is_active = 1;
        $admin->avatar = 'http://domain.com/avatar.jpg';
        $this->ensureEmployeeCode($admin);
        $admin->save();

        // Gán position admin cho user
        $admin->positions()->syncWithoutDetaching([
            $adminPosition->id => ['start_date' => $admin->join_date, 'end_date' => null],
        ]);

       

        // Staff user
        $staff = User::firstOrNew(['email' => 'staff@company.com']);
        $staff->name = 'Nguyen Van B';
        $staff->password = Hash::make('password123');
        $staff->phone_number = '0900000002';
        $staff->join_date = $staff->join_date ?: now()->toDateString();
        $staff->is_active = 1;
        $staff->avatar = null;
        $this->ensureEmployeeCode($staff);
        $staff->save();

        // Gán position staff cho user
        $staff->positions()->syncWithoutDetaching([
            $staffPosition->id => ['start_date' => $staff->join_date, 'end_date' => null],
        ]);


        // Inactive user
        $inactiveUser = User::firstOrNew(['email' => 'inactive@company.com']);
        $inactiveUser->name = 'Inactive User';
        $inactiveUser->password = Hash::make('password123');
        $inactiveUser->phone_number = '0900000003';
        $inactiveUser->join_date = $inactiveUser->join_date ?: now()->toDateString();
        $inactiveUser->is_active = 2;
        $inactiveUser->avatar = null;
        $this->ensureEmployeeCode($inactiveUser);
        $inactiveUser->save();

        $inactiveUser->positions()->syncWithoutDetaching([
            $staffPosition->id => ['start_date' => $inactiveUser->join_date, 'end_date' => null],
        ]);

       
    }
}
