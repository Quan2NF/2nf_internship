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
        Schema::create('issues', function (Blueprint $table) {
        $table->id();

        $table->foreignId('project_id')->constrained();
        $table->foreignId('created_by')->constrained('users');
        $table->foreignId('assigned_to')->nullable()->constrained('users');

        $table->string('title');
        $table->text('description')->nullable();

        $table->enum('type', ['bug', 'task', 'story']);
        $table->enum('priority', ['low', 'medium', 'high', 'critical']);
        $table->enum('status', ['open', 'in_progress', 'review', 'done', 'closed']);

        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
