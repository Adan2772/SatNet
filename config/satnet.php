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

    /*
    |--------------------------------------------------------------------------
    | Resumen semanal de cobros por correo
    |--------------------------------------------------------------------------
    |
    | Reporte enviado a todos los usuarios del panel con los clientes próximos
    | a vencer, en tolerancia y vencidos.
    |
    */

    // Días de anticipación para marcar una suscripción como "próxima a vencer".
    'aviso_dias' => (int) env('SATNET_AVISO_DIAS', 3),

    // Día de la semana en que se envía (0 = domingo, 1 = lunes ... 6 = sábado).
    'resumen_dia_semana' => (int) env('SATNET_RESUMEN_DIA_SEMANA', 1),

    'resumen_hora' => env('SATNET_RESUMEN_HORA', '08:00'),

];
