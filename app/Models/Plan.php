<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $table = 'planes';

    protected $fillable = [
        'nombre',
        'velocidad_mbps',
        'precio',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class);
    }
}
