<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Program;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Enrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => User::factory(),
            'program_id' => Program::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'middle_name' => $this->faker->optional()->firstName(),
            'suffix_name' => $this->faker->optional()->suffix(),
            'birthdate' => $this->faker->dateTimeBetween('-30 years', '-18 years')->format('Y-m-d'),
            'age' => $this->faker->numberBetween(18, 60),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'citizenship' => $this->faker->country(),
            'religion' => $this->faker->optional()->randomElement(['Catholic', 'Christian', 'Muslim', 'Buddhist', 'Other']),
            'place_of_birth' => $this->faker->city(),
            'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widowed', 'Divorced']),
            'spouse_name' => $this->faker->optional()->name(),
            'photo' => $this->faker->imageUrl(200, 200, 'people'),
            'status' => $this->faker->randomElement([
                Enrollment::STATUS_PENDING,
                Enrollment::STATUS_APPROVED,
                Enrollment::STATUS_REJECTED,
                Enrollment::STATUS_ENROLLED
            ]),
            'enrollment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'approved_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 month', 'now'),
            'rejected_at' => null,
            'rejection_reason' => null,
            'batch_number' => $this->faker->numberBetween(1, 100),
            'qr_pin' => $this->faker->optional()->numberBetween(100000, 999999),
            'or_number' => $this->faker->optional()->word(),
            'registration_fee_paid' => $this->faker->boolean(30), // 30% chance of being paid
            'paid_sessions' => $this->faker->numberBetween(0, 20),
            'completion_date' => null,
            'parent_consent' => $this->faker->boolean(),
            'father_name' => $this->faker->optional()->name('male'),
            'father_occupation' => $this->faker->optional()->jobTitle(),
            'father_contact' => $this->faker->optional()->phoneNumber(),
            'mother_name' => $this->faker->optional()->name('female'),
            'mother_occupation' => $this->faker->optional()->jobTitle(),
            'mother_contact' => $this->faker->optional()->phoneNumber(),
            'guardian' => $this->faker->optional()->name(),
            'guardian_relationship' => $this->faker->optional()->randomElement(['Aunt', 'Uncle', 'Grandparent', 'Sibling']),
            'guardian_contact' => $this->faker->optional()->phoneNumber(),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_relationship' => $this->faker->randomElement(['Parent', 'Sibling', 'Relative', 'Friend']),
            'emergency_contact_number' => $this->faker->phoneNumber(),
        ];
    }

    /**
     * Indicate that the enrollment is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Enrollment::STATUS_APPROVED,
            'approved_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the enrollment is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Enrollment::STATUS_PENDING,
            'approved_at' => null,
        ]);
    }

    /**
     * Indicate that the enrollment is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Enrollment::STATUS_REJECTED,
            'rejected_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'rejection_reason' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the registration fee is paid.
     */
    public function registrationFeePaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'registration_fee_paid' => true,
        ]);
    }

    /**
     * Indicate that the registration fee is unpaid.
     */
    public function registrationFeeUnpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'registration_fee_paid' => false,
        ]);
    }
}
