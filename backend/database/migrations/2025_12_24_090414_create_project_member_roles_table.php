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
        Schema::create('project_member_roles', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment

            $table->unsignedBigInteger('project_member_id');
            $table->unsignedBigInteger('role_id');

            $table->timestamps(); // created_at, updated_at

            // Unique composite index to prevent duplicate roles for the same project member
            $table->unique(['project_member_id', 'role_id'], 'project_member_roles_uq');

            // Foreign keys with explicit names
            $table->foreign('project_member_id', 'project_member_roles_project_member_fk')
                ->references('id')->on('project_members')
                ->cascadeOnDelete();

            $table->foreign('role_id', 'project_member_roles_role_fk')
                ->references('id')->on('roles')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_member_roles');
    }
};
