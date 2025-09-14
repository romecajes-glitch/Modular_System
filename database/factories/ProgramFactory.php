<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Program;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Program::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'registration_fee' => $this->faker->randomFloat(2, 100, 1000),
            'price_per_session' => $this->faker->randomFloat(2, 50, 200),
            'duration' => $this->faker->numberBetween(4, 52),
            //'duration_weeks' => $this->faker->numberBetween(4, 52),
            //'sessions_per_week' => $this->faker->numberBetween(1, 7),
            //'max_students' => $this->faker->numberBetween(10, 50),
            //'start_date' => $this->faker->dateTimeBetween('now', '+6 months'),
            //'end_date' => $this->faker->dateTimeBetween('+6 months', '+1 year'),
            //'status' => $this->faker->randomElement(['active', 'inactive', 'draft']),
        ];
    }
}
