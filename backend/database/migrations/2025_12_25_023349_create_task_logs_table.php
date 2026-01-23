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
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment, primary key
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->string('field', 100);
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamps(); // created_at & updated_at

            // Index
            $table->index('task_id', 'task_logs_task_idx');

            // Foreign keys with explicit names
            $table->foreign('task_id', 'tl_task_fk')
                ->references('id')->on('tasks')
                ->cascadeOnDelete();

            $table->foreign('user_id', 'tl_user_fk')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_logs');
    }
};
