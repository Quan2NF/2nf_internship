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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('subject', 255);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('priority_id');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('estimated_hours', 5, 2)->nullable();
            $table->decimal('actual_hours', 5, 2)->nullable();
            $table->integer('progress_rate')->default(0);
            $table->unsignedTinyInteger('is_private')->default(0);
            $table->timestamp('closed_at')->nullable();
            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at

            // Regular indexes
            $table->index('project_id', 'tasks_project_idx');
            $table->index('status_id', 'tasks_status_idx');
            $table->index('type_id', 'tasks_type_idx');
            $table->index('priority_id', 'tasks_priority_idx');
            $table->index('assigned_to', 'tasks_assigned_to_idx');
            $table->index('created_by', 'tasks_created_by_idx');

            // Foreign keys with explicit names
            $table->foreign('project_id', 'tasks_project_fk')
                ->references('id')->on('projects')
                ->cascadeOnDelete();

            $table->foreign('status_id', 'tasks_status_fk')
                ->references('id')->on('task_statuses')
                ->cascadeOnDelete();

            $table->foreign('type_id', 'tasks_type_fk')
                ->references('id')->on('task_types')
                ->cascadeOnDelete();

            $table->foreign('priority_id', 'tasks_priority_fk')
                ->references('id')->on('task_priorities')
                ->cascadeOnDelete();

            $table->foreign('assigned_to', 'tasks_assigned_to_fk')
                ->references('id')->on('users')
                ->cascadeOnDelete();

            $table->foreign('created_by', 'tasks_created_by_fk')
                ->references('id')->on('users')
                ->cascadeOnDelete();

            // Self-referencing parent_id
            $table->foreign('parent_id', 'tasks_parent_fk')
                ->references('id')->on('tasks')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
