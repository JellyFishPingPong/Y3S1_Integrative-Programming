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
        Schema::create('forum_comments', function (Blueprint $table) {
            $table->id('comment_id'); // Auto-incrementing primary key
            $table->text('comment'); // The comment content
            $table->unsignedBigInteger('parent')->nullable(); // Nullable parent comment (for replies)
            $table->unsignedBigInteger('forum_id'); // Foreign key for forum
            $table->unsignedBigInteger('user_id'); // Foreign key for user

            // Foreign key constraints
            $table->foreign('forum_id')->references('forum_id')->on('forums')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parent')->references('comment_id')->on('forum_comments')->onDelete('cascade')->onUpdate('cascade'); // Self-referencing foreign key for replies

            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_comments');
    }
};
