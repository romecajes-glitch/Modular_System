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
            $table->string('father_name')->nullable()->after('phone');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('guardian')->nullable()->after('mother_name');
            $table->string('guardian_contact')->nullable()->after('guardian');
            $table->string('address')->nullable()->after('guardian_contact');
            $table->string('citizenship')->nullable()->after('address');
            $table->string('religion')->nullable()->after('citizenship');
            $table->string('place_of_birth')->nullable()->after('religion');
            $table->string('civil_status')->nullable()->after('place_of_birth');
            $table->string('spouse_name')->nullable()->after('civil_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn([
                'father_name',
                'mother_name',
                'guardian',
                'guardian_contact',
                'address',
                'citizenship',
                'religion',
                'place_of_birth',
                'civil_status',
                'spouse_name'
            ]);
        });
    }
};
