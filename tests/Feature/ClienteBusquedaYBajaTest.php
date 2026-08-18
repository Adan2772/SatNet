<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteBusquedaYBajaTest extends TestCase
{
    use RefreshDatabase;

    public function test_busca_clientes_por_nombre(): void
    {
        $admin = User::factory()->create();
        Cliente::factory()->create(['nombre' => 'María Torres']);
        Cliente::factory()->create(['nombre' => 'Luis Gómez']);

        $response = $this->actingAs($admin)->get(route('clientes.index', ['q' => 'maría']));

        $response->assertOk();
        $response->assertSee('María Torres');
        $response->assertDontSee('Luis Gómez');
    }

    public function test_busca_clientes_por_telefono(): void
    {
        $admin = User::factory()->create();
        Cliente::factory()->create(['nombre' => 'Cliente Uno', 'telefono' => '555-1234']);
        Cliente::factory()->create(['nombre' => 'Cliente Dos', 'telefono' => '555-9999']);

        $response = $this->actingAs($admin)->get(route('clientes.index', ['q' => '1234']));

        $response->assertSee('Cliente Uno');
        $response->assertDontSee('Cliente Dos');
    }

    public function test_dar_de_baja_y_reactivar_un_cliente(): void
    {
        $admin = User::factory()->create();
        $cliente = Cliente::factory()->create(['activo' => true]);

        $this->actingAs($admin)
            ->post(route('clientes.toggle-activo', $cliente))
            ->assertRedirect(route('clientes.show', $cliente));

        $this->assertFalse($cliente->refresh()->activo);

        $this->actingAs($admin)->post(route('clientes.toggle-activo', $cliente));

        $this->assertTrue($cliente->refresh()->activo);
    }
}
