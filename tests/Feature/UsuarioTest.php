<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsuarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_crea_un_usuario_nuevo(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post(route('usuarios.store'), [
            'name' => 'Nuevo Admin',
            'email' => 'nuevo@satnet.test',
            'password' => 'contrasena123',
            'password_confirmation' => 'contrasena123',
        ])->assertRedirect(route('usuarios.index'));

        $this->assertDatabaseHas('users', ['email' => 'nuevo@satnet.test', 'name' => 'Nuevo Admin']);

        $creado = User::where('email', 'nuevo@satnet.test')->first();
        $this->assertTrue(Hash::check('contrasena123', $creado->password));
    }

    public function test_rechaza_un_correo_ya_registrado(): void
    {
        $admin = User::factory()->create();
        User::factory()->create(['email' => 'ocupado@satnet.test']);

        $response = $this->actingAs($admin)->post(route('usuarios.store'), [
            'name' => 'Otro',
            'email' => 'ocupado@satnet.test',
            'password' => 'contrasena123',
            'password_confirmation' => 'contrasena123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_edita_nombre_y_correo_sin_tocar_la_contrasena(): void
    {
        $admin = User::factory()->create();
        $usuario = User::factory()->create(['name' => 'Original', 'password' => Hash::make('original123')]);

        $this->actingAs($admin)->put(route('usuarios.update', $usuario), [
            'name' => 'Actualizado',
            'email' => $usuario->email,
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect(route('usuarios.index'));

        $usuario->refresh();
        $this->assertSame('Actualizado', $usuario->name);
        $this->assertTrue(Hash::check('original123', $usuario->password));
    }

    public function test_edita_la_contrasena_cuando_se_proporciona(): void
    {
        $admin = User::factory()->create();
        $usuario = User::factory()->create();

        $this->actingAs($admin)->put(route('usuarios.update', $usuario), [
            'name' => $usuario->name,
            'email' => $usuario->email,
            'password' => 'nuevaClave123',
            'password_confirmation' => 'nuevaClave123',
        ]);

        $this->assertTrue(Hash::check('nuevaClave123', $usuario->refresh()->password));
    }

    public function test_no_se_puede_eliminar_la_propia_cuenta(): void
    {
        $admin = User::factory()->create();
        User::factory()->create();

        $this->actingAs($admin)
            ->delete(route('usuarios.destroy', $admin))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_elimina_un_usuario_que_no_es_el_actual(): void
    {
        $admin = User::factory()->create();
        $otro = User::factory()->create();

        $this->actingAs($admin)
            ->delete(route('usuarios.destroy', $otro))
            ->assertRedirect(route('usuarios.index'));

        $this->assertDatabaseMissing('users', ['id' => $otro->id]);
    }
}
