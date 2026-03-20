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
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('students_school_reg_unique');
            $table->unique(['school_id', 'class_id', 'registration_number'], 'students_school_class_reg_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('students_school_class_reg_unique');
            $table->unique(['school_id', 'registration_number'], 'students_school_reg_unique');
        });
    }
};
