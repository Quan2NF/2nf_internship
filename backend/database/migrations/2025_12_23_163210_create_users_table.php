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
Schema::create('users', function (Blueprint $table) {
    $table->id();

    $table->string('employee_code', 20)->unique();
    $table->string('name', 100);
    $table->string('email', 100)->unique();
    $table->string('password');

    $table->string('phone_number', 30)->nullable();
    $table->date('birthday')->nullable();
    $table->tinyInteger('gender')->nullable()->comment('1: Male, 2: Female, 3: Other');

    $table->date('join_date')->nullable();
    $table->date('resign_date')->nullable();

    $table->string('avatar', 255)->nullable();
    $table->tinyInteger('is_active')->default(1)->comment('1: Active, 2: Inactive');

    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();

    $table->index(['employee_code', 'email', 'is_active']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
