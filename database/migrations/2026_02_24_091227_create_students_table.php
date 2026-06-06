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
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->string('admission_number')->unique(); // e.g., SCH/2026/001
        $table->string('class_arm'); // e.g., JSS 1A or SSS 3C
        $table->string('parent_phone'); // For SMS alerts
        $table->decimal('fees_balance', 10, 2)->default(0); // Financial tracking
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};