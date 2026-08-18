<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'telefono' => $this->faker->numerify('555-####'),
            'correo' => $this->faker->unique()->safeEmail(),
            'direccion' => $this->faker->streetAddress(),
            'activo' => true,
        ];
    }
}
