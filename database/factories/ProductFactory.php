<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Establishment;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product_type = ProductType::factory()->create();
        $employee = Employee::factory()->create();
        $establishment = Establishment::factory()->create();
        return [
            'barcode' => $this->faker->word,
            'image' => null,
            'serial_number' => $this->faker->word,
            'product_type_id' => $product_type->id,
            'employee_id' => $employee->id,
            'establishment_id' => $establishment->id,
            'acquisition_cost' => $this->faker->randomFloat(2, 0, 1000),
            'siga_code' => null,
            'accounting_account' => null,
            'license_plate' => $this->faker->unique()->word,
        ];
    }
}
