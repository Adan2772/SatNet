<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Suscripcion extends Model
{
    protected $table = 'suscripciones';

    protected $fillable = [
        'cliente_id',
        'plan_id',
        'dia_pago',
        'fecha_proximo_pago',
        'activa',
    ];

    protected $casts = [
        'fecha_proximo_pago' => 'date',
        'activa' => 'boolean',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function recordatorios(): HasMany
    {
        return $this->hasMany(RecordatorioLog::class);
    }

    public function enlace(): HasOne
    {
        return $this->hasOne(Enlace::class);
    }

    /**
     * al_dia | tolerancia | vencido, calculado a partir de fecha_proximo_pago
     * y el periodo de tolerancia configurado — nunca se guarda en base de datos.
     */
    protected function estado(): Attribute
    {
        return Attribute::get(function (): string {
            $hoy = Carbon::today();
            $limiteTolerancia = $this->fecha_proximo_pago
                ->copy()
                ->addDays((int) config('satnet.tolerancia_dias'));

            if ($hoy->lt($this->fecha_proximo_pago)) {
                return 'al_dia';
            }

            return $hoy->lte($limiteTolerancia) ? 'tolerancia' : 'vencido';
        });
    }

    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopeConVencimientoHoy($query)
    {
        return $query->activas()->whereDate('fecha_proximo_pago', Carbon::today());
    }

    /**
     * Próxima fecha de pago para un día de corte (1-31) a partir de una fecha dada,
     * ajustando meses cortos (ej. día 31 en febrero) al último día del mes.
     */
    public static function calcularProximaFecha(int $diaPago, ?Carbon $desde = null): Carbon
    {
        $desde ??= Carbon::today();

        $fecha = $desde->copy()->day(min($diaPago, $desde->daysInMonth));

        if ($fecha->lt($desde)) {
            $fecha = $fecha->addMonthNoOverflow();
            $fecha->day(min($diaPago, $fecha->daysInMonth));
        }

        return $fecha;
    }

    /**
     * Fecha de pago del siguiente ciclo, anclada al día de corte original
     * (ej. si se paga el día 3, el próximo vencimiento sigue siendo el día 15).
     */
    public static function siguienteCiclo(Carbon $fechaActual, int $diaPago): Carbon
    {
        $siguiente = $fechaActual->copy()->addMonthNoOverflow();

        return $siguiente->day(min($diaPago, $siguiente->daysInMonth));
    }
}
