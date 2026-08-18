<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guarda de regresión: Laravel singulariza "planes" a "plane" (regla del
 * inglés) por defecto, lo que rompía el route model binding porque los
 * controladores tipan Plan $plan. Si esta ruta vuelve a usar el nombre de
 * parámetro por defecto, estos tests fallan.
 */
class PlanEdicionRegresionTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_ruta_de_edicion_recibe_el_plan_real_no_uno_vacio(): void
    {
        $admin = User::factory()->create();
        $plan = Plan::factory()->create(['nombre' => 'Plan Original']);

        $response = $this->actingAs($admin)->get(route('planes.edit', $plan));

        $response->assertOk();
        $response->assertSee('Editar oferta');
        $response->assertSee('Plan Original', escape: false);
    }

    public function test_actualizar_un_plan_persiste_los_cambios(): void
    {
        $admin = User::factory()->create();
        $plan = Plan::factory()->create(['precio' => 250]);

        $this->actingAs($admin)->put(route('planes.update', $plan), [
            'nombre' => $plan->nombre,
            'velocidad_mbps' => $plan->velocidad_mbps,
            'precio' => 275,
            'activo' => '1',
        ])->assertRedirect(route('planes.index'));

        $this->assertSame('275.00', $plan->refresh()->precio);
    }

    public function test_eliminar_un_plan_lo_borra_de_verdad(): void
    {
        $admin = User::factory()->create();
        $plan = Plan::factory()->create();

        $this->actingAs($admin)->delete(route('planes.destroy', $plan))
            ->assertRedirect(route('planes.index'));

        $this->assertDatabaseMissing('planes', ['id' => $plan->id]);
    }
}
