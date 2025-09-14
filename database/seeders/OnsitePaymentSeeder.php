<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class OnsitePaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some approved enrollments with OR numbers for onsite payment testing
        $approvedEnrollments = Enrollment::factory()->count(5)->create([
            'status' => Enrollment::STATUS_APPROVED,
            'or_number' => function () {
                return 'OR-' . rand(100000, 999999);
            },
            'approved_at' => now()->subDays(rand(1, 30)),
        ]);

        $this->command->info('Created ' . $approvedEnrollments->count() . ' approved enrollments with OR numbers for onsite payment testing.');

        // Also create some enrollments that are approved but don't have OR numbers yet
        $pendingOrEnrollments = Enrollment::factory()->count(3)->create([
            'status' => Enrollment::STATUS_APPROVED,
            'or_number' => null,
            'approved_at' => now()->subDays(rand(1, 30)),
        ]);

        $this->command->info('Created ' . $pendingOrEnrollments->count() . ' approved enrollments without OR numbers (for testing OR number assignment).');

        // Create some regular pending enrollments
        $pendingEnrollments = Enrollment::factory()->count(5)->create([
            'status' => Enrollment::STATUS_PENDING,
        ]);

        $this->command->info('Created ' . $pendingEnrollments->count() . ' pending enrollments for regular enrollment management.');

        $this->command->info('Onsite Payment Seeder completed successfully!');
    }
}
