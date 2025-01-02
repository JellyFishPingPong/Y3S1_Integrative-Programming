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
        Schema::create('forums', function (Blueprint $table) {
            $table->id('forum_id'); // Auto-incrementing primary key
            $table->string('title', 255); // Title as a string (varchar)
            $table->longText('content'); // Content as longtext
            $table->unsignedBigInteger('user_id'); // Foreign key referencing users table

            // Foreign key constraint on user_id with cascade on delete
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forums');
    }
};
