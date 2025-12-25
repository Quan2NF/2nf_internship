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
        Schema::create('wiki_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wiki_id');
            $table->longText('content');
            $table->timestamps();

            // Unique index
            $table->unique('wiki_id', 'wiki_contents_wiki_idx');

            // Foreign key with cascade on delete
            $table->foreign('wiki_id', 'wiki_contents_wiki_fk')
                ->references('id')->on('wikis')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wiki_contents');
    }
};
