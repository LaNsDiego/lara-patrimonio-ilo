<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sector>
 */
class SectorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sector_type = \App\Models\SectorType::factory()->create();
        return [
            'name' => $this->faker->name,
            'sector_type_id' => $sector_type->id,
            'description' => $this->faker->text,
            'geojson' => $this->faker->text,
            'parent_sector_id' => null,
        ];
    }
}
