<?php

namespace Tests\Unit;

use App\Models\Suscripcion;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SuscripcionFechasTest extends TestCase
{
    public function test_calcula_proxima_fecha_dentro_del_mismo_mes_si_el_dia_no_ha_pasado(): void
    {
        $fecha = Suscripcion::calcularProximaFecha(15, Carbon::parse('2026-08-10'));

        $this->assertSame('2026-08-15', $fecha->format('Y-m-d'));
    }

    public function test_calcula_proxima_fecha_en_el_siguiente_mes_si_el_dia_ya_paso(): void
    {
        $fecha = Suscripcion::calcularProximaFecha(5, Carbon::parse('2026-08-10'));

        $this->assertSame('2026-09-05', $fecha->format('Y-m-d'));
    }

    public function test_calcula_proxima_fecha_ajusta_dia_31_a_febrero_corto(): void
    {
        $fecha = Suscripcion::calcularProximaFecha(31, Carbon::parse('2026-02-01'));

        $this->assertSame('2026-02-28', $fecha->format('Y-m-d'));
    }

    public function test_siguiente_ciclo_avanza_un_mes_anclado_al_dia_de_pago(): void
    {
        $siguiente = Suscripcion::siguienteCiclo(Carbon::parse('2026-05-10'), 10);

        $this->assertSame('2026-06-10', $siguiente->format('Y-m-d'));
    }

    public function test_siguiente_ciclo_se_recupera_del_recorte_de_mes_corto(): void
    {
        // día de pago 31: en enero cae en el 31, en febrero se recorta a 28,
        // pero en marzo debe volver a caer en el día 31 (no quedarse en 28 para siempre).
        $febrero = Suscripcion::siguienteCiclo(Carbon::parse('2026-01-31'), 31);
        $this->assertSame('2026-02-28', $febrero->format('Y-m-d'));

        $marzo = Suscripcion::siguienteCiclo($febrero, 31);
        $this->assertSame('2026-03-31', $marzo->format('Y-m-d'));
    }

    public function test_ciclo_anterior_retrocede_un_mes_anclado_al_dia_de_pago(): void
    {
        $anterior = Suscripcion::cicloAnterior(Carbon::parse('2026-09-05'), 5);

        $this->assertSame('2026-08-05', $anterior->format('Y-m-d'));
    }
}
