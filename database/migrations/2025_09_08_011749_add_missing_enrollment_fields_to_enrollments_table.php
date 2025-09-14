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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->timestamp('enrollment_date')->nullable()->after('student_id');
            $table->string('father_occupation')->nullable()->after('father_name');
            $table->string('father_contact')->nullable()->after('father_occupation');
            $table->string('mother_occupation')->nullable()->after('mother_name');
            $table->string('mother_contact')->nullable()->after('mother_occupation');
            $table->string('guardian_relationship')->nullable()->after('guardian');
            $table->string('emergency_contact_name')->nullable()->after('guardian_contact');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_number')->nullable()->after('emergency_contact_relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn([
                'enrollment_date',
                'father_occupation',
                'father_contact',
                'mother_occupation',
                'mother_contact',
                'guardian_relationship',
                'emergency_contact_name',
                'emergency_contact_relationship',
                'emergency_contact_number'
            ]);
        });
    }
};
