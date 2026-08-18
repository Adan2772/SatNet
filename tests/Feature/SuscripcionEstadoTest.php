<?php

namespace Tests\Feature;

use App\Models\Suscripcion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SuscripcionEstadoTest extends TestCase
{
    use RefreshDatabase;

    public function test_esta_al_dia_si_la_fecha_de_pago_es_futura(): void
    {
        $suscripcion = Suscripcion::factory()->venceEn(1)->create();

        $this->assertSame('al_dia', $suscripcion->estado);
    }

    public function test_entra_en_tolerancia_el_mismo_dia_del_vencimiento(): void
    {
        $suscripcion = Suscripcion::factory()->venceEn(0)->create();

        $this->assertSame('tolerancia', $suscripcion->estado);
    }

    public function test_sigue_en_tolerancia_en_el_ultimo_dia_del_periodo_configurado(): void
    {
        $dias = (int) config('satnet.tolerancia_dias');

        $suscripcion = Suscripcion::factory()->venceEn(-$dias)->create();

        $this->assertSame('tolerancia', $suscripcion->estado);
    }

    public function test_pasa_a_vencido_un_dia_despues_del_periodo_de_tolerancia(): void
    {
        $dias = (int) config('satnet.tolerancia_dias');

        $suscripcion = Suscripcion::factory()->venceEn(-($dias + 1))->create();

        $this->assertSame('vencido', $suscripcion->estado);
    }

    public function test_scope_activas_excluye_suscripciones_de_clientes_dados_de_baja(): void
    {
        $suscripcion = Suscripcion::factory()->venceEn(0)->create();

        $this->assertTrue(Suscripcion::activas()->whereKey($suscripcion->id)->exists());

        $suscripcion->cliente->update(['activo' => false]);

        $this->assertFalse(Suscripcion::activas()->whereKey($suscripcion->id)->exists());
    }
}
