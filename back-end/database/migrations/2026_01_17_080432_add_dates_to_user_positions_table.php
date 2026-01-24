<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_positions', function (Blueprint $table) {
            if (!Schema::hasColumn('user_positions', 'start_date')) {
                $table->date('start_date')->nullable()->after('position_id');
            }
            if (!Schema::hasColumn('user_positions', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_positions', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('user_positions', 'start_date')) {
                $columnsToDrop[] = 'start_date';
            }
            if (Schema::hasColumn('user_positions', 'end_date')) {
                $columnsToDrop[] = 'end_date';
            }
            if ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};