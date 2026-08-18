<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Plan;
use App\Models\Suscripcion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Suscripcion>
 */
class SuscripcionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaProximoPago = Carbon::today();

        return [
            'cliente_id' => Cliente::factory(),
            'plan_id' => Plan::factory(),
            'dia_pago' => $fechaProximoPago->day,
            'fecha_proximo_pago' => $fechaProximoPago,
            'activa' => true,
        ];
    }

    public function venceEn(int $dias): self
    {
        return $this->state(function () use ($dias) {
            $fecha = Carbon::today()->addDays($dias);

            return [
                'dia_pago' => $fecha->day,
                'fecha_proximo_pago' => $fecha,
            ];
        });
    }
}
