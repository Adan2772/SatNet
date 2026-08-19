<?php

namespace App\Console\Commands;

use App\Mail\ResumenCobros;
use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('satnet:enviar-resumen-cobros')]
#[Description('Envía a los usuarios del panel un resumen de clientes próximos a vencer, en tolerancia y vencidos.')]
class EnviarResumenCobros extends Command
{
    public function handle(): void
    {
        $diasAviso = (int) config('satnet.aviso_dias');

        $proximas = Suscripcion::query()
            ->proximasAVencer($diasAviso)
            ->with(['cliente', 'plan'])
            ->orderBy('fecha_proximo_pago')
            ->get();

        $activas = Suscripcion::query()->activas()->with(['cliente', 'plan'])->get();
        $tolerancia = $activas->where('estado', 'tolerancia')->sortBy('fecha_proximo_pago')->values();
        $vencidas = $activas->where('estado', 'vencido')->sortBy('fecha_proximo_pago')->values();

        $usuarios = User::all();

        if ($usuarios->isEmpty()) {
            $this->warn('No hay usuarios registrados; no se envió el resumen.');

            return;
        }

        foreach ($usuarios as $usuario) {
            Mail::to($usuario->email)->send(new ResumenCobros($proximas, $tolerancia, $vencidas));
        }

        $this->info(
            "Resumen enviado a {$usuarios->count()} usuario(s): "
            ."{$proximas->count()} próximas a vencer, {$tolerancia->count()} en tolerancia, {$vencidas->count()} vencidas."
        );
    }
}
