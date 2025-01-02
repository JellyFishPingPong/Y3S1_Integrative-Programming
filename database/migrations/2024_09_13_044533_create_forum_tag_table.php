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
        Schema::create('forum_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('forum_id'); // Foreign key for forum
            $table->unsignedBigInteger('tag_id');   // Foreign key for tag

            // Define foreign key constraints
            $table->foreign('forum_id')->references('forum_id')->on('forums')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tag_id')->references('tag_id')->on('tags')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps(); // Optional timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_tag');
    }
};
