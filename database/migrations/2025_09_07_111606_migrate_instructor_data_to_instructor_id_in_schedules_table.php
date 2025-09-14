<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate instructor names to instructor_id
        $schedules = DB::table('schedules')->get();

        foreach ($schedules as $schedule) {
            if ($schedule->instructor) {
                $user = DB::table('users')->where('name', $schedule->instructor)->first();
                if ($user) {
                    DB::table('schedules')->where('id', $schedule->id)->update([
                        'instructor_id' => $user->id,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse migration: set instructor_id to null
        DB::table('schedules')->update(['instructor_id' => null]);
    }
};
