<?php

namespace Database\Factories;

use App\Models\Establishment;
use App\Models\JobTitle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $job_title = JobTitle::factory()->create();
        $establishment = Establishment::factory()->create();
        return [
            'document_type' => 'DNI',
            'document_number' => $this->faker->regexify('[0-9]{8}'),
            'name' => $this->faker->name(),
            'job_title_id' => $job_title->id,
            'establishment_id' => $establishment->id,
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }
}
