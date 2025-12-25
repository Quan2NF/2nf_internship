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
        Schema::create('task_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('version_id');
            $table->timestamps();

            // Unique indexes
            $table->unique('task_id', 'task_versions_task_idx');
            $table->unique('version_id', 'task_versions_version_idx');

            // Foreign keys with cascade on delete
            $table->foreign('task_id', 'task_versions_task_fk')
                ->references('id')->on('tasks')
                ->cascadeOnDelete();

            $table->foreign('version_id', 'task_versions_version_fk')
                ->references('id')->on('versions')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_versions');
    }
};
