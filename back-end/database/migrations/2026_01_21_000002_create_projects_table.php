<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique('projects_code_uq');
            $table->string('name', 100);
            $table->text('description')->nullable();

            // NOTE: Spec says `enum` but does not provide allowed values.
            // Using string keeps DB flexible; can be switched to enum later once values are confirmed.
            $table->string('status', 50);

            $table->date('planned_start_date')->nullable();
            $table->date('planned_end_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->integer('progress_rate')->default(0);
            $table->unsignedTinyInteger('is_public')->default(0);
            $table->unsignedTinyInteger('is_active')->default(1);

            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
