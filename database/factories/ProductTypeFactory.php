<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\MeasurementUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductType>
 */
class ProductTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brand = Brand::factory()->create();
        $mesaurement_unit = MeasurementUnit::factory()->create();
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->text(),
            'model' => $this->faker->word(),
            'tags' => $this->faker->text,
            'brand_id' => $brand->id,
            'measurement_unit_id' => $mesaurement_unit->id,
        ];
    }
}
