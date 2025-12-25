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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Unique indexes
            $table->unique('project_id', 'documents_project_idx');
            $table->unique('created_by', 'documents_user_idx');

            // Foreign keys with cascade on delete
            $table->foreign('project_id', 'documents_project_fk')
                ->references('id')->on('projects')
                ->cascadeOnDelete();

            $table->foreign('created_by', 'documents_user_fk')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
