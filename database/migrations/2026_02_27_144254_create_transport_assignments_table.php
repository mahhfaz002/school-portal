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
    Schema::create('transport_assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained();
        $table->foreignId('route_id')->constrained();
        $table->foreignId('vehicle_id')->constrained();
        $table->string('pick_up_point');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_assignments');
    }
};
