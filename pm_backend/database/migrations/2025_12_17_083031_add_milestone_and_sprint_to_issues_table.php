<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->string('milestone')->nullable()->after('status');
            $table->string('sprint')->nullable()->after('milestone');
            if (Schema::hasColumn('issues', 'assigned_to')) {
                $table->renameColumn('assigned_to', 'assigner');
            }
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn(['milestone', 'sprint']);
            if (Schema::hasColumn('issues', 'assigner')) {
                $table->renameColumn('assigner', 'assigned_to');
            }
        });
    }
};
