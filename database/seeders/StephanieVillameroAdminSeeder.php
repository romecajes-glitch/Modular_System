<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StephanieVillameroAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'StephanieVillamero'],
            [
                'name' => 'Stephanie Villamero',
                'username' => 'StephanieVillamero',
                'email' => 'stephanie.villamero@bnsc.edu.ph',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );
        
        $this->command->info('Stephanie Villamero admin account created successfully!');
    }
}
