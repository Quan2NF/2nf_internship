<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing data FIRST to convert string values to integers
        DB::statement("UPDATE projects SET status = 1 WHERE status = 'planning'");
        DB::statement("UPDATE projects SET status = 2 WHERE status = 'active'");
        DB::statement("UPDATE projects SET status = 3 WHERE status = 'on_hold'");
        DB::statement("UPDATE projects SET status = 4 WHERE status = 'completed'");
        DB::statement("UPDATE projects SET status = 5 WHERE status = 'cancelled'");
        DB::statement("UPDATE projects SET status = 4 WHERE status = 'archived'");

        // Then change the column
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('status')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Revert to enum
            $table->enum('status', ['planning', 'active', 'completed', 'archived'])->default('planning')->change();
        });
    }
};
