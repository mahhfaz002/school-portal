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
    Schema::create('borrow_records', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained();
        $table->foreignId('book_id')->constrained();
        $table->date('borrowed_at');
        $table->date('due_at');
        $table->date('returned_at')->nullable();
        $table->decimal('fine_amount', 8, 2)->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_records');
    }
};
