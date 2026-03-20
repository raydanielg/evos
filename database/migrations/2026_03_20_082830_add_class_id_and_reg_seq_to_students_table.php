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
            $table->foreignId('class_id')->nullable()->after('school_id')->constrained('school_classes')->onDelete('restrict');
            $table->unsignedInteger('reg_seq')->nullable()->after('registration_number');
            $table->unique(['school_id', 'class_id', 'reg_seq'], 'students_school_class_seq_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('students_school_class_seq_unique');
            $table->dropForeign(['class_id']);
            $table->dropColumn(['class_id', 'reg_seq']);
        });
    }
};
