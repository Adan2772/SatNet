<?php

namespace App\Console\Commands;

use App\Mail\RecordatorioPago;
use App\Models\RecordatorioLog;
use App\Models\Suscripcion;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('satnet:evaluar-suscripciones')]
#[Description('Envía el recordatorio de pago a las suscripciones que hoy inician su periodo de tolerancia.')]
class EvaluarSuscripciones extends Command
{
    public function handle(): void
    {
        $suscripciones = Suscripcion::query()
            ->conVencimientoHoy()
            ->with('cliente')
            ->get();

        foreach ($suscripciones as $suscripcion) {
            $correo = $suscripcion->cliente->correo;
            $exito = (bool) $correo;

            if ($correo) {
                Mail::to($correo)->send(new RecordatorioPago($suscripcion));
            }

            RecordatorioLog::create([
                'suscripcion_id' => $suscripcion->id,
                'enviado_en' => now(),
                'exito' => $exito,
            ]);
        }

        $this->info("Recordatorios evaluados: {$suscripciones->count()}");
    }
}
