<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Program;
use App\Models\User;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all programs
        $programs = Program::all();

        // Get instructor users
        $johnDoe = User::where('name', 'John Doe')->first();
        $janeSmith = User::where('name', 'Jane Smith')->first();

        if ($programs->count() > 0) {
            // Create sample schedules for each program
            foreach ($programs as $program) {
                if ($johnDoe) {
                    Schedule::create([
                        'program_id' => $program->id,
                        'instructor_id' => $johnDoe->id,
                        'day' => 'Monday',
                        'start_time' => '09:00:00',
                        'end_time' => '11:00:00'
                    ]);
                }

                if ($janeSmith) {
                    Schedule::create([
                        'program_id' => $program->id,
                        'instructor_id' => $janeSmith->id,
                        'day' => 'Wednesday',
                        'start_time' => '14:00:00',
                        'end_time' => '16:00:00'
                    ]);
                }
            }
        }
    }
}
