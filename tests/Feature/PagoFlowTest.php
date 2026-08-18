<?php

namespace Tests\Feature;

use App\Mail\ReciboPago;
use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PagoFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registrar_pago_avanza_el_ciclo_genera_recibo_y_envia_correo(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        $suscripcion = Suscripcion::factory()->venceEn(0)->create([
            'dia_pago' => now()->day,
        ]);
        $suscripcion->cliente->update(['correo' => 'cliente@ejemplo.com']);
        $fechaOriginal = $suscripcion->fecha_proximo_pago->copy();

        $response = $this->actingAs($admin)->post(route('suscripciones.pagos.store', $suscripcion), [
            'monto' => 350,
            'notas' => 'Pago de prueba',
        ]);

        $response->assertRedirect(route('clientes.show', $suscripcion->cliente));

        $suscripcion->refresh();
        $this->assertTrue($suscripcion->fecha_proximo_pago->gt($fechaOriginal));
        $this->assertSame(1, $suscripcion->pagos()->count());

        $pago = $suscripcion->pagos()->first();
        $this->assertNotNull($pago->recibo);
        $this->assertNotNull($pago->recibo->enviado_en);

        Mail::assertSent(ReciboPago::class, fn ($mail) => $mail->recibo->id === $pago->recibo->id);
    }

    public function test_no_envia_correo_si_el_cliente_no_tiene_correo_registrado(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        $suscripcion = Suscripcion::factory()->venceEn(0)->create();
        $suscripcion->cliente->update(['correo' => null]);

        $this->actingAs($admin)->post(route('suscripciones.pagos.store', $suscripcion), [
            'monto' => 200,
        ]);

        Mail::assertNothingSent();
        $this->assertNull($suscripcion->pagos()->first()->recibo->enviado_en);
    }

    public function test_editar_pago_actualiza_el_monto(): void
    {
        $admin = User::factory()->create();
        $suscripcion = Suscripcion::factory()->venceEn(0)->create();
        $pago = $suscripcion->pagos()->create(['monto' => 100, 'fecha_pago' => now()]);

        $this->actingAs($admin)->put(route('suscripciones.pagos.update', [$suscripcion, $pago]), [
            'monto' => 275.50,
            'notas' => 'Corregido',
        ])->assertRedirect(route('clientes.show', $suscripcion->cliente));

        $this->assertSame('275.50', $pago->refresh()->monto);
    }

    public function test_anular_el_pago_mas_reciente_revierte_el_ciclo_y_lo_borra(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        $suscripcion = Suscripcion::factory()->venceEn(0)->create([
            'dia_pago' => now()->day,
        ]);
        $fechaOriginal = $suscripcion->fecha_proximo_pago->copy();

        $this->actingAs($admin)->post(route('suscripciones.pagos.store', $suscripcion), ['monto' => 300]);

        $suscripcion->refresh();
        $pago = $suscripcion->pagos()->first();

        $this->actingAs($admin)
            ->delete(route('suscripciones.pagos.destroy', [$suscripcion, $pago]))
            ->assertRedirect(route('clientes.show', $suscripcion->cliente));

        $suscripcion->refresh();
        $this->assertSame($fechaOriginal->format('Y-m-d'), $suscripcion->fecha_proximo_pago->format('Y-m-d'));
        $this->assertSame(0, $suscripcion->pagos()->count());
        $this->assertDatabaseMissing('recibos', ['pago_id' => $pago->id]);
    }

    public function test_no_se_puede_anular_un_pago_que_no_es_el_mas_reciente(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        $suscripcion = Suscripcion::factory()->venceEn(0)->create([
            'dia_pago' => now()->day,
        ]);

        $this->actingAs($admin)->post(route('suscripciones.pagos.store', $suscripcion), ['monto' => 300]);
        $suscripcion->refresh();
        $primerPago = $suscripcion->pagos()->first();

        $this->actingAs($admin)->post(route('suscripciones.pagos.store', $suscripcion), ['monto' => 300]);
        $suscripcion->refresh();

        $this->actingAs($admin)
            ->delete(route('suscripciones.pagos.destroy', [$suscripcion, $primerPago]))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('pagos', ['id' => $primerPago->id]);
    }
}
