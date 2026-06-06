<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('timetable_slots', function (Blueprint $table) {
        $table->id();
        $table->string('class_arm'); // e.g., JSS 1A
        $table->string('subject_name'); // e.g., Mathematics
        $table->string('teacher_name');
        $table->string('room_name');
        $table->string('day_of_week'); // e.g., Monday
        $table->time('start_time');
        $table->time('end_time');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetable_slots');
    }
};
