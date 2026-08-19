<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('satnet:evaluar-suscripciones')
    ->dailyAt(config('satnet.evaluacion_hora'));

Schedule::command('satnet:enviar-resumen-cobros')
    ->weeklyOn(config('satnet.resumen_dia_semana'), config('satnet.resumen_hora'));
