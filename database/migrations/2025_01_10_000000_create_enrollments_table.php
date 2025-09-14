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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix_name')->nullable();
            $table->date('birthdate');
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('photo');

            // ✅ New column for admin approval logic
            $table->string('status')->default('pending'); // can be 'pending', 'approved', 'rejected'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
