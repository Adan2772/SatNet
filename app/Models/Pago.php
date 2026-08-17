<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'suscripcion_id',
        'monto',
        'fecha_pago',
        'notas',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'date',
    ];

    public function suscripcion(): BelongsTo
    {
        return $this->belongsTo(Suscripcion::class);
    }

    public function recibo(): HasOne
    {
        return $this->hasOne(Recibo::class);
    }
}
