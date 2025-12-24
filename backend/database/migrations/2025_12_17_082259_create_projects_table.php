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
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment

            $table->string('code', 100);
            $table->string('name', 100);
            $table->text('description')->nullable();

            $table->unsignedTinyInteger('status');

            $table->date('planned_start_date')->nullable();
            $table->date('planned_end_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->unsignedInteger('progress_rate');

            $table->unsignedTinyInteger('is_public')->default(0);
            $table->unsignedTinyInteger('is_active')->default(1);

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            $table->timestamps();
            $table->softDeletes();

            // INDEXES
            $table->unique('code', 'projects_code_uq');
            
            // FOREIGN KEYS
            $table->foreign('created_by', 'projects_created_by_fk')
                ->references('id')->on('users');

            $table->foreign('updated_by', 'projects_updated_by_fk')
                ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
