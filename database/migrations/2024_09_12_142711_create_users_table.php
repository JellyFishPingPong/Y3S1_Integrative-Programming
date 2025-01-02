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
            $table->id('user_id'); // Auto-incrementing primary key
            $table->string('username', 255); // Username as a string (varchar)
            $table->string('email', 255)->unique(); // Email as a unique string (varchar)
            $table->string('passwd', 255); // Password (varchar, long enough to store hashed passwords)
            $table->binary('picture')->nullable(); // Picture as BLOB (nullable)

            $table->timestamps(); // Adds created_at and updated_at columns
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
