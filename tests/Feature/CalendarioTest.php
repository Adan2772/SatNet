<?php

namespace Tests\Feature;

use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_invitado_no_puede_ver_el_calendario(): void
    {
        $this->get(route('calendario'))->assertRedirect(route('login'));
    }

    public function test_agrupa_clientes_por_rango_de_dia_de_pago(): void
    {
        $admin = User::factory()->create();

        $inicio = Suscripcion::factory()->create(['dia_pago' => 3]);
        $inicio->cliente->update(['nombre' => 'Cliente Inicio Mes']);

        $mitad = Suscripcion::factory()->create(['dia_pago' => 15]);
        $mitad->cliente->update(['nombre' => 'Cliente Mitad Mes']);

        $fin = Suscripcion::factory()->create(['dia_pago' => 28]);
        $fin->cliente->update(['nombre' => 'Cliente Fin Mes']);

        $response = $this->actingAs($admin)->get(route('calendario'));

        $response->assertOk();
        $response->assertSeeInOrder(['Días 1–10', 'Día 3', 'Cliente Inicio Mes']);
        $response->assertSeeInOrder(['Días 11–20', 'Día 15', 'Cliente Mitad Mes']);
        $response->assertSeeInOrder(['Días 21–31', 'Día 28', 'Cliente Fin Mes']);
    }

    public function test_excluye_suscripciones_de_clientes_dados_de_baja(): void
    {
        $admin = User::factory()->create();

        $suscripcion = Suscripcion::factory()->create(['dia_pago' => 10]);
        $suscripcion->cliente->update(['nombre' => 'Cliente De Baja', 'activo' => false]);

        $response = $this->actingAs($admin)->get(route('calendario'));

        $response->assertOk();
        $response->assertDontSee('Cliente De Baja');
    }
}
