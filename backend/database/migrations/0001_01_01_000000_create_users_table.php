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
            $table->id(); // bigint unsigned auto_increment

            $table->string('employee_code', 20);
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('password', 255);

            $table->string('phone_number', 15)->nullable();
            $table->date('birthday')->nullable();
            $table->unsignedTinyInteger('gender')->nullable()
                  ->comment('1: Male, 2: Female, 3: Other');

            $table->date('join_date')->nullable();
            $table->date('resign_date')->nullable();

            $table->string('avatar', 255)->nullable();

            $table->unsignedTinyInteger('is_active')->default(1);

            $table->rememberToken();

            $table->timestamps();   // created_at, updated_at
            $table->softDeletes();  // deleted_at
            
            // INDEXES
            $table->unique('employee_code', 'users_employee_code_uq');
            $table->unique('email', 'users_email_uq');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
