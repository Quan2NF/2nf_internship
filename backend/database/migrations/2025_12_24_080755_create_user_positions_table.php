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
        Schema::create('user_positions', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('position_id');

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->timestamps(); // created_at, updated_at

            // Unique composite index
            $table->unique(['user_id', 'position_id'], 'user_positions_uq');

            // Foreign keys with explicit names
            $table->foreign('user_id', 'user_positions_user_fk')
                ->references('id')->on('users')
                ->cascadeOnDelete();

            $table->foreign('position_id', 'user_positions_position_fk')
                ->references('id')->on('positions')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_positions');
    }
};
