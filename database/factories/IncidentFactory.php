<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Sector;
use App\Models\WantedPerson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Incident>
 */
class IncidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $wanted_person = WantedPerson::factory()->create();
        $vehicle = Product::factory()->create();
        $staff_security = Employee::factory()->create();
        $sector = Sector::factory()->create();
        return [
            //format 24h
            'time' => $this->faker->time('H:i'),
            'wanted_person_id' => $wanted_person->id,
            'staff_security_id' => $staff_security->id,
            'vehicle_plate' => $vehicle->license_plate,
            'code' => $this->faker->randomNumber(4),
            'sector_id' => $sector->id,
            'vehicle_id' => $vehicle->id,
            'reason' => $this->faker->sentence,
            'action_to_take' => $this->faker->sentence,
        ];
    }
}
