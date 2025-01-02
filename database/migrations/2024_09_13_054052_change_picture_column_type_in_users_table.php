<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePictureColumnTypeInUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Change the 'picture' column type from binary to string and make it nullable
            $table->string('picture')->nullable()->change();

            // Add a new 'name' column of type string with a maximum length of 255 characters
            $table->string('name', 255);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert the 'picture' column type to binary and nullable (if it was previously nullable)
            $table->binary('picture')->nullable()->change();

            // Drop the 'name' column if the migration is rolled back
            $table->dropColumn('name');
        });
    }
}
