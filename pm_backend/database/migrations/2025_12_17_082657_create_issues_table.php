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

    $table->string('title');
    $table->string('code')->unique();

    // Quan hệ
    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
    $table->foreignId('parent_id')->nullable()->constrained('issues');

    // Người tạo & người được assign
    $table->foreignId('created_by')->constrained('users');
    $table->foreignId('assigned_to')->nullable()->constrained('users');

    // Trạng thái
    $table->enum('status', [
        'open', 'todo', 'in_progress', 'reviewing',
        're_open', 'done', 'close'
    ])->default('open');

    $table->string('priority')->default('medium');

    // Thời gian
    $table->date('start_date')->nullable();
    $table->date('due_date')->nullable();

    $table->integer('estimate_time')->nullable();
    $table->integer('actual_time')->nullable();

    $table->text('description')->nullable();

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
