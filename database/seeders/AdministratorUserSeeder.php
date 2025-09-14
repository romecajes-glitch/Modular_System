<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdministratorUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'Administrator'],
            [
                'name' => 'Administrator',
                'username' => 'Administrator',
                'email' => 'administrator@bnsc.edu.ph',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
        
        $this->command->info('Administrator user created successfully!');
    }
}
