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
        Schema::create('project_members', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamps(); // created_at, updated_at

            // Unique composite index to prevent duplicate user assignments
            $table->unique(['project_id', 'user_id'], 'project_members_uq');

            // Foreign keys with explicit names
            $table->foreign('project_id', 'project_members_project_fk')
                ->references('id')->on('projects')
                ->cascadeOnDelete();

            $table->foreign('user_id', 'project_members_user_fk')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_members');
    }
};
