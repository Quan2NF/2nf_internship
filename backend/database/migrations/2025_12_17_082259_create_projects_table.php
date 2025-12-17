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
            $table->string('name');
            $table->string('code')->unique();

            $table->date('kickoff_date')->nullable();
            $table->date('end_date')->nullable();

            $table->text('description')->nullable();

            $table->enum('status', ['planned', 'active', 'completed', 'archived'])->default('planned');

            $table->foreignId('pm_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // created_at, updated_at, deleted_at
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index('status');
            $table->index('kickoff_date');
            $table->index('pm_id');
            $table->index('code');
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
