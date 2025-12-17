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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('issue_code')->unique();
            $table->foreignId('parent_id')->nullable()
                  ->constrained('issues')->nullOnDelete();
            $table->text('description')->nullable();
            $table->enum('status', ['open', 'todo', 'in_progress', 'reviewing', 're_open', 'done', 'close'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');

            $table->foreignId('milestone_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sprint_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('estimate_time', 8, 2)->nullable();
            $table->decimal('actual_time', 8, 2)->nullable();
            
            // created_at, updated_at, deleted_at
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['project_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
