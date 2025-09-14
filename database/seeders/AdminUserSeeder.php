<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bnsc.edu.ph'],
            [
                'name' => 'Administrator',
                'username' => 'Administrator', // Remove if your table doesn't have 'username'
                'password' => Hash::make('password123'),
                'role' => 'admin', // Remove if your table doesn't have 'role'
            ]
        );
    }
}
