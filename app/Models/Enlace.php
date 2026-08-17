<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enlace extends Model
{
    protected $table = 'enlaces';

    protected $fillable = [
        'suscripcion_id',
        'nombre',
        'ip_asignada',
        'mac_address',
        'tipo_antena',
        'nodo',
        'numero_serie',
        'fecha_instalacion',
        'estado',
        'latitud',
        'longitud',
    ];

    protected $casts = [
        'fecha_instalacion' => 'date',
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
    ];

    public function suscripcion(): BelongsTo
    {
        return $this->belongsTo(Suscripcion::class);
    }
}
