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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            // Link to the student and delete scores if student is removed
            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            // Link to the subject
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');

            // Academic Data
            $table->integer('ca_score')->default(0);    // Continuous Assessment (usually /40)
            $table->integer('exam_score')->default(0);  // Exam Score (usually /60)
            $table->string('term');                     // 1st Term, 2nd Term, 3rd Term
            $table->string('session');                  // e.g., 2025/2026

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
