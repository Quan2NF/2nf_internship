<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: add columns (safe for partial/failed runs)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'employee_code')) {
                // Keep nullable first to avoid duplicate '' on existing rows
                $table->string('employee_code', 20)->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 15)->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'birthday')) {
                $table->date('birthday')->nullable()->after('phone_number');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->unsignedTinyInteger('gender')->nullable()->after('birthday'); // 1/2/3
            }
            if (!Schema::hasColumn('users', 'join_date')) {
                $table->date('join_date')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'resign_date')) {
                $table->date('resign_date')->nullable()->after('join_date');
            }
        });

        // Step 2: backfill employee_code for existing rows (guaranteed unique by id)
        DB::statement("UPDATE `users` SET `employee_code` = CONCAT('EMP', LPAD(`id`, 6, '0')) WHERE `employee_code` IS NULL OR `employee_code` = ''");

        // Step 3: enforce NOT NULL + UNIQUE after backfill
        DB::statement("ALTER TABLE `users` MODIFY `employee_code` VARCHAR(20) NOT NULL");
        $hasEmployeeCodeUnique = DB::selectOne("SHOW INDEX FROM `users` WHERE Key_name = 'users_employee_code_unique'");
        if (!$hasEmployeeCodeUnique) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('employee_code');
            });
        }

        // Step 4: align is_active with spec (1 = Active, 2 = Inactive) without requiring doctrine/dbal
        DB::statement("ALTER TABLE `users` MODIFY `is_active` TINYINT UNSIGNED NOT NULL DEFAULT 1");
        DB::statement("UPDATE `users` SET `is_active` = CASE WHEN `is_active` IS NULL OR `is_active` = 1 THEN 1 ELSE 2 END");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Nếu rollback đơn giản: drop các cột mới
            $table->dropUnique(['employee_code']);
            $table->dropColumn([
                'employee_code',
                'phone_number',
                'birthday',
                'gender',
                'join_date',
                'resign_date',
            ]);

            // revert is_active 
            // $table->boolean('is_active')->default(true)->change();
        });
    }
};