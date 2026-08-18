<?php

namespace Tests\Feature;

use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnlaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guarda_los_datos_tecnicos_del_enlace(): void
    {
        $admin = User::factory()->create();
        $suscripcion = Suscripcion::factory()->venceEn(0)->create();

        $this->actingAs($admin)->put(route('suscripciones.enlace.update', $suscripcion), [
            'nombre' => 'ENL-0001',
            'ip_asignada' => '10.10.1.5',
            'tipo_antena' => 'Ubiquiti LiteBeam',
            'nodo' => 'Torre Centro',
            'fecha_instalacion' => '2026-01-15',
            'estado' => 'activo',
        ])->assertRedirect(route('clientes.show', $suscripcion->cliente));

        $this->assertDatabaseHas('enlaces', [
            'suscripcion_id' => $suscripcion->id,
            'nombre' => 'ENL-0001',
            'ip_asignada' => '10.10.1.5',
        ]);
    }

    public function test_rechaza_una_ip_ya_asignada_a_otro_enlace(): void
    {
        $admin = User::factory()->create();
        $suscripcionA = Suscripcion::factory()->venceEn(0)->create();
        $suscripcionB = Suscripcion::factory()->venceEn(0)->create();

        $suscripcionA->enlace()->create([
            'nombre' => 'ENL-A',
            'ip_asignada' => '10.10.1.5',
            'fecha_instalacion' => now(),
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)->put(route('suscripciones.enlace.update', $suscripcionB), [
            'nombre' => 'ENL-B',
            'ip_asignada' => '10.10.1.5',
            'fecha_instalacion' => now()->format('Y-m-d'),
            'estado' => 'activo',
        ]);

        $response->assertSessionHasErrors('ip_asignada');
        $this->assertDatabaseMissing('enlaces', ['nombre' => 'ENL-B']);
    }

    public function test_permite_guardar_el_mismo_enlace_sin_disparar_su_propia_validacion_de_unicidad(): void
    {
        $admin = User::factory()->create();
        $suscripcion = Suscripcion::factory()->venceEn(0)->create();

        $suscripcion->enlace()->create([
            'nombre' => 'ENL-0001',
            'ip_asignada' => '10.10.1.5',
            'fecha_instalacion' => now(),
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)->put(route('suscripciones.enlace.update', $suscripcion), [
            'nombre' => 'ENL-0001',
            'ip_asignada' => '10.10.1.5',
            'fecha_instalacion' => now()->format('Y-m-d'),
            'estado' => 'suspendido',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('enlaces', ['suscripcion_id' => $suscripcion->id, 'estado' => 'suspendido']);
    }
}
