<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recibo extends Model
{
    protected $table = 'recibos';

    protected $fillable = [
        'pago_id',
        'folio',
        'enviado_en',
    ];

    protected $casts = [
        'enviado_en' => 'datetime',
    ];

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class);
    }
}
