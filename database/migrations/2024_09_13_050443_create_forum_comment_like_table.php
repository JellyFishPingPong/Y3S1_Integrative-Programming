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
        Schema::create('forum_comment_like', function (Blueprint $table) {
            $table->unsignedBigInteger('comment_id'); // Foreign key for forum comment
            $table->unsignedBigInteger('user_id');    // Foreign key for user

            // Define foreign key constraints
            $table->foreign('comment_id')->references('comment_id')->on('forum_comments')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps(); // Optional timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_comment_like');
    }
};
