<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InstructorUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create instructor users that match the schedule data
        User::updateOrCreate(
            ['email' => 'john.doe@bnsc.edu.ph'],
            [
                'name' => 'John Doe',
                'username' => 'john.doe',
                'password' => Hash::make('password123'),
                'role' => 'instructor',
            ]
        );

        User::updateOrCreate(
            ['email' => 'jane.smith@bnsc.edu.ph'],
            [
                'name' => 'Jane Smith',
                'username' => 'jane.smith',
                'password' => Hash::make('password123'),
                'role' => 'instructor',
            ]
        );

        $this->command->info('Instructor users created successfully!');
    }
}
