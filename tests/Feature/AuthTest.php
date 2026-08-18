<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_invitado_no_puede_ver_el_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_inicia_sesion_con_credenciales_correctas(): void
    {
        $admin = User::factory()->create(['password' => bcrypt('secreto123')]);

        $response = $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'secreto123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_rechaza_credenciales_incorrectas(): void
    {
        $admin = User::factory()->create(['password' => bcrypt('secreto123')]);

        $response = $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'incorrecta',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
