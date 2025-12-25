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
        Schema::create('wikis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->timestamps();

            // Unique index
            $table->unique('project_id', 'wikis_project_idx');

            // Foreign key with cascade on delete
            $table->foreign('project_id', 'wikis_project_fk')
                ->references('id')->on('projects')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wikis');
    }
};
