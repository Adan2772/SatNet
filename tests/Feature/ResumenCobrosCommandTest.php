<?php

namespace Tests\Feature;

use App\Mail\ResumenCobros;
use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ResumenCobrosCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_envia_el_resumen_a_cada_usuario_con_las_listas_correctas(): void
    {
        Mail::fake();

        $admin1 = User::factory()->create(['email' => 'admin1@satnet.test']);
        $admin2 = User::factory()->create(['email' => 'admin2@satnet.test']);

        $diasAviso = (int) config('satnet.aviso_dias');

        $proxima = Suscripcion::factory()->venceEn($diasAviso)->create();
        $proxima->cliente->update(['nombre' => 'Cliente Proxima']);

        $fueraDeVentana = Suscripcion::factory()->venceEn($diasAviso + 5)->create();
        $fueraDeVentana->cliente->update(['nombre' => 'Cliente Lejano']);

        $enTolerancia = Suscripcion::factory()->venceEn(-1)->create();
        $enTolerancia->cliente->update(['nombre' => 'Cliente Tolerancia']);

        $vencida = Suscripcion::factory()->venceEn(-((int) config('satnet.tolerancia_dias') + 1))->create();
        $vencida->cliente->update(['nombre' => 'Cliente Vencido']);

        $this->artisan('satnet:enviar-resumen-cobros')->assertSuccessful();

        Mail::assertSent(ResumenCobros::class, 2);

        Mail::assertSent(ResumenCobros::class, function ($mail) use ($proxima, $enTolerancia, $vencida) {
            return $mail->hasTo('admin1@satnet.test')
                && $mail->proximas->pluck('id')->contains($proxima->id)
                && $mail->proximas->count() === 1
                && $mail->tolerancia->pluck('id')->contains($enTolerancia->id)
                && $mail->vencidas->pluck('id')->contains($vencida->id);
        });
    }

    public function test_no_envia_nada_si_no_hay_usuarios(): void
    {
        Mail::fake();

        Suscripcion::factory()->venceEn(1)->create();

        $this->artisan('satnet:enviar-resumen-cobros')->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_no_incluye_suscripciones_de_clientes_dados_de_baja(): void
    {
        Mail::fake();

        User::factory()->create();

        $suscripcion = Suscripcion::factory()->venceEn(1)->create();
        $suscripcion->cliente->update(['activo' => false]);

        $this->artisan('satnet:enviar-resumen-cobros')->assertSuccessful();

        Mail::assertSent(ResumenCobros::class, function ($mail) use ($suscripcion) {
            return $mail->proximas->pluck('id')->doesntContain($suscripcion->id);
        });
    }
}
