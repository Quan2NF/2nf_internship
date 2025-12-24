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
        Schema::create('positions', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment

            $table->string('code', 100);
            $table->string('name', 100);
            $table->unsignedTinyInteger('scope')->default(1)
                ->comment('1: Project, 2: System');

            $table->timestamps();    // created_at, updated_at
            $table->softDeletes();   // deleted_at

            // Indexes
            $table->unique('code', 'positions_code_uq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
