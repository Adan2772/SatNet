<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class ReciboController extends Controller
{
    public function show(Recibo $recibo): Response
    {
        $recibo->load('pago.suscripcion.cliente', 'pago.suscripcion.plan');

        return Pdf::loadView('recibos.pdf', ['recibo' => $recibo])
            ->stream($recibo->folio.'.pdf');
    }
}
