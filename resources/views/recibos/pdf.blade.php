<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #16233a; font-size: 12px; }
        .header { border-bottom: 2px solid #0f2e5c; padding-bottom: 12px; margin-bottom: 20px; }
        .brand { font-size: 18px; font-weight: bold; color: #0f2e5c; }
        .folio { color: #5a6b80; }
        table.datos { width: 100%; border-collapse: collapse; margin-top: 16px; }
        table.datos td { padding: 6px 0; border-bottom: 1px solid #cfe0f2; }
        table.datos td.label { color: #5a6b80; width: 40%; }
        table.datos td.valor { text-align: right; font-weight: bold; }
        .total { margin-top: 20px; text-align: right; font-size: 16px; font-weight: bold; color: #0f2e5c; }
        .footer { margin-top: 40px; font-size: 10px; color: #5a6b80; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">{{ config('app.name') }}</div>
        <div class="folio">Recibo {{ $recibo->folio }}</div>
    </div>

    <table class="datos">
        <tr>
            <td class="label">Cliente</td>
            <td class="valor">{{ $recibo->pago->suscripcion->cliente->nombre }}</td>
        </tr>
        <tr>
            <td class="label">Plan</td>
            <td class="valor">{{ $recibo->pago->suscripcion->plan->nombre }} ({{ $recibo->pago->suscripcion->plan->velocidad_mbps }} Mb)</td>
        </tr>
        <tr>
            <td class="label">Fecha de pago</td>
            <td class="valor">{{ $recibo->pago->fecha_pago->translatedFormat('d \d\e F \d\e Y') }}</td>
        </tr>
        <tr>
            <td class="label">Próximo pago</td>
            <td class="valor">{{ $recibo->pago->suscripcion->fecha_proximo_pago->translatedFormat('d \d\e F \d\e Y') }}</td>
        </tr>
        @if ($recibo->pago->notas)
            <tr>
                <td class="label">Notas</td>
                <td class="valor">{{ $recibo->pago->notas }}</td>
            </tr>
        @endif
    </table>

    <div class="total">Total pagado: ${{ number_format($recibo->pago->monto, 2) }}</div>

    <div class="footer">Generado automáticamente por {{ config('app.name') }} el {{ now()->translatedFormat('d/m/Y H:i') }}.</div>
</body>
</html>
