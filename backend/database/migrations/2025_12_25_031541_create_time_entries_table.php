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
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment, primary key
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->date('spent_on');
            $table->decimal('hours', 5, 2);
            $table->text('comment')->nullable();
            $table->timestamps(); // created_at & updated_at

            // Indexes
            $table->unique('task_id', 'time_entries_task_idx');
            $table->unique('user_id', 'time_entries_user_idx');

            // Foreign keys with explicit names
            $table->foreign('task_id', 'time_entries_task_fk')
                ->references('id')->on('tasks')
                ->cascadeOnDelete();

            $table->foreign('user_id', 'time_entries_user_fk')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};
