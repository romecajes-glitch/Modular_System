<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'gucorcajes@gmail.com'],
            [
                'name' => 'Stephanie Villamero',
                'username' => 'StephanieVillamero', // Remove if your table doesn't have 'username'
                'password' => Hash::make('password123'),
                'role' => 'admin', // Remove if your table doesn't have 'role'
            ]
        );
    }
}
