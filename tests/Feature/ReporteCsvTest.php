<?php

namespace Tests\Feature;

use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReporteCsvTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_reporte_muestra_el_total_del_rango(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        $suscripcion = Suscripcion::factory()->venceEn(0)->create();
        $suscripcion->pagos()->create(['monto' => 150, 'fecha_pago' => now()]);
        $suscripcion->pagos()->create(['monto' => 100, 'fecha_pago' => now()->subMonth()]);

        $response = $this->actingAs($admin)->get(route('reportes.pagos', [
            'desde' => now()->startOfMonth()->format('Y-m-d'),
            'hasta' => now()->endOfMonth()->format('Y-m-d'),
        ]));

        $response->assertOk();
        $response->assertSee('150.00');
        $response->assertDontSee('$250.00');
    }

    public function test_exporta_csv_con_los_pagos_del_rango(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        $suscripcion = Suscripcion::factory()->venceEn(0)->create();
        $suscripcion->cliente->update(['nombre' => 'Cliente CSV']);
        $suscripcion->pagos()->create(['monto' => 199.99, 'fecha_pago' => now()]);

        $response = $this->actingAs($admin)->get(route('reportes.pagos.exportar', [
            'desde' => now()->startOfMonth()->format('Y-m-d'),
            'hasta' => now()->endOfMonth()->format('Y-m-d'),
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $contenido = $response->streamedContent();
        $this->assertStringContainsString('Cliente CSV', $contenido);
        $this->assertStringContainsString('199.99', $contenido);
    }
}
