<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->string('parent_email');
            $table->string('desired_class');
            $table->string('status')->default('pending'); // pending, approved/admitted, rejected
            $table->string('reason')->nullable();
            // Uploaded supporting documents
            $table->string('passport_path')->nullable();
            $table->string('birth_cert_path')->nullable();
            $table->string('indigene_letter_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
