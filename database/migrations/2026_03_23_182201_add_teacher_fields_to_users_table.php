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
    Schema::table('users', function (Blueprint $table) {
        // Only add if it doesn't exist
        if (!Schema::hasColumn('users', 'subject_assigned')) {
            $table->string('subject_assigned')->nullable();
        }

        if (!Schema::hasColumn('users', 'must_change_password')) {
            $table->boolean('must_change_password')->default(true);
        }

        // We skip class_assigned because the error proves it's already there!
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
