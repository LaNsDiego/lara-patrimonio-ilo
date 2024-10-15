<?php

namespace Database\Factories;

use App\Models\EstablishmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Establishment>
 */
class EstablishmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $establishment_type = EstablishmentType::factory()->create();
        return [

            'code' => $this->faker->unique()->countryCode(),
            'acronym' => $this->faker->unique()->word,
            'name' => $this->faker->word,
            'establishment_type_id' => $establishment_type->id,
        ];
    }
}
