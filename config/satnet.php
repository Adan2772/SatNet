<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Periodo de tolerancia
    |--------------------------------------------------------------------------
    |
    | Días después de la fecha de pago durante los cuales una suscripción se
    | considera "en tolerancia" en vez de "vencida".
    |
    */

    'tolerancia_dias' => (int) env('SATNET_TOLERANCIA_DIAS', 5),

    /*
    |--------------------------------------------------------------------------
    | Hora de evaluación diaria
    |--------------------------------------------------------------------------
    |
    | Hora a la que corre la tarea programada que envía recordatorios de pago.
    |
    */

    'evaluacion_hora' => env('SATNET_EVALUACION_HORA', '08:00'),

];
