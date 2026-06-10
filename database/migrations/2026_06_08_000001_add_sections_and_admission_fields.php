<?php

use App\Models\SchoolClass;
use App\Support\Sections;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            if (!Schema::hasColumn('classes', 'section')) {
                $table->string('section')->nullable()->after('name');
            }
        });

        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'section')) {
                $table->string('section')->nullable()->after('name');
            }
        });

        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'section')) {
                $table->string('section')->nullable()->after('class_arm');
            }
        });

        Schema::table('applicants', function (Blueprint $table) {
            foreach (['address', 'section', 'fslc_path', 'junior_waec_path'] as $col) {
                if (!Schema::hasColumn('applicants', $col)) {
                    $table->string($col)->nullable();
                }
            }
        });

        // Backfill class sections from their names.
        foreach (SchoolClass::all() as $class) {
            if (!$class->section) {
                $class->section = Sections::fromClassName($class->name);
                $class->saveQuietly();
            }
        }
    }

    public function down(): void
    {
        Schema::table('classes', fn (Blueprint $t) => $t->dropColumn('section'));
        Schema::table('subjects', fn (Blueprint $t) => $t->dropColumn('section'));
        Schema::table('students', fn (Blueprint $t) => $t->dropColumn('section'));
        Schema::table('applicants', function (Blueprint $t) {
            foreach (['address', 'section', 'fslc_path', 'junior_waec_path'] as $col) {
                if (Schema::hasColumn('applicants', $col)) {
                    $t->dropColumn($col);
                }
            }
        });
    }
};
