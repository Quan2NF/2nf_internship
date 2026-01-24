<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: add columns (safe for partial/failed runs)
        Schema::table('positions', function (Blueprint $table) {
            if (!Schema::hasColumn('positions', 'code')) {
                // Keep nullable first to avoid duplicate '' on existing rows
                $table->string('code', 100)->nullable()->after('id');
            }
            if (!Schema::hasColumn('positions', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Step 2: backfill code for existing rows (guaranteed unique by id)
        DB::statement("UPDATE `positions` SET `code` = CONCAT('POS', LPAD(`id`, 6, '0')) WHERE `code` IS NULL OR `code` = ''");

        // Step 3: enforce NOT NULL + UNIQUE after backfill
        DB::statement("ALTER TABLE `positions` MODIFY `code` VARCHAR(100) NOT NULL");
        $hasCodeUnique = DB::selectOne("SHOW INDEX FROM `positions` WHERE Key_name = 'positions_code_unique'");
        if (!$hasCodeUnique) {
            Schema::table('positions', function (Blueprint $table) {
                $table->unique('code');
            });
        }
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            if (Schema::hasColumn('positions', 'code')) {
                $table->dropUnique(['code']);
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('positions', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};