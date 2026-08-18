<?php

namespace Tests\Feature;

use App\Mail\RecordatorioPago;
use App\Models\Suscripcion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RecordatorioCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_envia_recordatorio_solo_a_suscripciones_que_vencen_hoy(): void
    {
        Mail::fake();

        $vencesHoy = Suscripcion::factory()->venceEn(0)->create();
        $vencesHoy->cliente->update(['correo' => 'hoy@ejemplo.com']);

        $vencioAyer = Suscripcion::factory()->venceEn(-1)->create();
        $vencioAyer->cliente->update(['correo' => 'ayer@ejemplo.com']);

        $venceManana = Suscripcion::factory()->venceEn(1)->create();
        $venceManana->cliente->update(['correo' => 'manana@ejemplo.com']);

        $this->artisan('satnet:evaluar-suscripciones')->assertSuccessful();

        Mail::assertSent(RecordatorioPago::class, 1);
        Mail::assertSent(RecordatorioPago::class, fn ($mail) => $mail->suscripcion->id === $vencesHoy->id);

        $this->assertDatabaseHas('recordatorio_logs', [
            'suscripcion_id' => $vencesHoy->id,
            'exito' => true,
        ]);
        $this->assertDatabaseCount('recordatorio_logs', 1);
    }

    public function test_registra_recordatorio_sin_exito_si_el_cliente_no_tiene_correo(): void
    {
        Mail::fake();

        $suscripcion = Suscripcion::factory()->venceEn(0)->create();
        $suscripcion->cliente->update(['correo' => null]);

        $this->artisan('satnet:evaluar-suscripciones')->assertSuccessful();

        Mail::assertNothingSent();
        $this->assertDatabaseHas('recordatorio_logs', [
            'suscripcion_id' => $suscripcion->id,
            'exito' => false,
        ]);
    }

    public function test_no_envia_recordatorio_a_clientes_dados_de_baja(): void
    {
        Mail::fake();

        $suscripcion = Suscripcion::factory()->venceEn(0)->create();
        $suscripcion->cliente->update(['correo' => 'baja@ejemplo.com', 'activo' => false]);

        $this->artisan('satnet:evaluar-suscripciones')->assertSuccessful();

        Mail::assertNothingSent();
        $this->assertDatabaseCount('recordatorio_logs', 0);
    }
}
