<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_member_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_member_id')->constrained('project_members');
            $table->foreignId('role_id')->constrained('roles');
            $table->timestamps();

            $table->unique(['project_member_id', 'role_id'], 'project_member_roles_uq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_member_roles');
    }
};
