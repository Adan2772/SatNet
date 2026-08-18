<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => 'Plan '.$this->faker->numberBetween(5, 50).' Mb',
            'velocidad_mbps' => $this->faker->numberBetween(5, 50),
            'precio' => $this->faker->randomFloat(2, 200, 600),
            'descripcion' => $this->faker->sentence(),
            'activo' => true,
        ];
    }
}
