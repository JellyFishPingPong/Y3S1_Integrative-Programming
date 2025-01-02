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
        Schema::create('forum_like', function (Blueprint $table) {
            $table->unsignedBigInteger('forum_id'); // Foreign key for forum
            $table->unsignedBigInteger('user_id');  // Foreign key for user

            // Define foreign key constraints
            $table->foreign('forum_id')->references('forum_id')->on('forums')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps(); // Optional, add this if you want timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_like');
    }
};
