<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecordatorioLog extends Model
{
    protected $table = 'recordatorio_logs';

    protected $fillable = [
        'suscripcion_id',
        'enviado_en',
        'exito',
    ];

    protected $casts = [
        'enviado_en' => 'datetime',
        'exito' => 'boolean',
    ];

    public function suscripcion(): BelongsTo
    {
        return $this->belongsTo(Suscripcion::class);
    }
}
