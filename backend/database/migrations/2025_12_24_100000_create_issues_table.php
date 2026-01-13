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

            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->tinyInteger('type')->comment('1: Bug, 2: Feature, 3: Improvement, 4: Task');
            $table->tinyInteger('priority')->comment('1: Low, 2: Medium, 3: High, 4: Urgent');
            $table->tinyInteger('status')->default(1)->comment('1: Open, 2: In Progress, 3: Resolved, 4: Closed');

            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('reported_by');

            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->decimal('actual_hours', 8, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->text('resolution')->nullable();

            $table->boolean('is_public')->default(false);
            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('reported_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['project_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index(['reported_by', 'status']);
            $table->index(['priority', 'status']);
            $table->index(['type', 'status']);
            $table->index('due_date');
            $table->index('is_active');
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
