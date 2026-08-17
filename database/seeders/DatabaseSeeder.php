<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Plan;
use App\Models\Recibo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador SATNET',
            'email' => 'admin@satnet.test',
        ]);

        $planBasico = Plan::create([
            'nombre' => 'Plan 10 Mb',
            'velocidad_mbps' => 10,
            'precio' => 250,
            'descripcion' => 'Ideal para navegación y redes sociales.',
        ]);

        $planEstandar = Plan::create([
            'nombre' => 'Plan 20 Mb',
            'velocidad_mbps' => 20,
            'precio' => 350,
            'descripcion' => 'El más elegido: streaming y trabajo remoto.',
        ]);

        $planPremium = Plan::create([
            'nombre' => 'Plan 30 Mb',
            'velocidad_mbps' => 30,
            'precio' => 500,
            'descripcion' => 'Para casas con varios dispositivos conectados a la vez.',
        ]);

        $clientes = [
            ['nombre' => 'María Torres', 'plan' => $planEstandar, 'dias' => -2],
            ['nombre' => 'Luis Gómez', 'plan' => $planBasico, 'dias' => -9],
            ['nombre' => 'Carla Ruiz', 'plan' => $planPremium, 'dias' => 15],
            ['nombre' => 'Jorge Medina', 'plan' => $planBasico, 'dias' => 3],
            ['nombre' => 'Ana Delgado', 'plan' => $planEstandar, 'dias' => 0],
            ['nombre' => 'Pedro Salinas', 'plan' => $planEstandar, 'dias' => -1],
        ];

        foreach ($clientes as $i => $datos) {
            $fechaProximoPago = Carbon::today()->addDays($datos['dias']);

            $cliente = Cliente::create([
                'nombre' => $datos['nombre'],
                'telefono' => '555-01'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
                'correo' => strtolower(str_replace(' ', '.', $datos['nombre'])).'@ejemplo.com',
                'direccion' => 'Calle Demo #'.($i + 10),
            ]);

            $suscripcion = $cliente->suscripciones()->create([
                'plan_id' => $datos['plan']->id,
                'dia_pago' => $fechaProximoPago->day,
                'fecha_proximo_pago' => $fechaProximoPago,
            ]);

            // Carla Ruiz ya tiene un pago anterior en su historial, con recibo.
            if ($datos['nombre'] === 'Carla Ruiz') {
                $pago = $suscripcion->pagos()->create([
                    'monto' => $datos['plan']->precio,
                    'fecha_pago' => Carbon::today()->subMonthNoOverflow(),
                    'notas' => null,
                ]);

                Recibo::create([
                    'pago_id' => $pago->id,
                    'folio' => 'REC-'.$pago->fecha_pago->format('Ymd').'-0001',
                    'enviado_en' => $pago->fecha_pago,
                ]);
            }
        }
    }
}
