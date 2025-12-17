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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Thông tin dự án
            $table->string('name');
            $table->string('code')->unique();
            $table->date('kickoff_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();

            // planning, active, completed, closed
            $table->string('status')->default('planning');

            // Quan hệ user
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('pm_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Laravel chuẩn
            $table->timestamps();
            $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
