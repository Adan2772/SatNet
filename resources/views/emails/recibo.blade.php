<!doctype html>
<html>
<body style="margin:0; padding:32px 16px; background:#f3f6fa; font-family: Arial, sans-serif; color:#16233a;">
    <table role="presentation" width="100%" style="max-width:480px; margin:0 auto;">
        <tr>
            <td style="padding-bottom:20px;">
                <span style="display:inline-block; width:32px; height:32px; line-height:32px; text-align:center; background:#0f2e5c; color:#fff; border-radius:8px; font-weight:bold;">S</span>
                <span style="font-weight:bold; font-size:18px; margin-left:8px;">{{ config('app.name') }}</span>
            </td>
        </tr>
        <tr>
            <td style="background:#ffffff; border:1px solid #cfe0f2; border-radius:12px; padding:28px;">
                <h1 style="margin:0 0 12px; font-size:20px;">¡Gracias por tu pago!</h1>
                <p style="margin:0 0 16px; font-size:14px; line-height:1.6; color:#2a3648;">
                    Confirmamos tu pago del plan <strong>{{ $recibo->pago->suscripcion->plan->nombre }}</strong>.
                    Adjuntamos tu recibo <strong>{{ $recibo->folio }}</strong> en PDF.
                </p>
                <table role="presentation" width="100%" style="font-size:14px; margin:0; border-collapse:collapse;">
                    <tr>
                        <td style="padding:6px 0; color:#5a6b80;">Folio</td>
                        <td style="padding:6px 0; text-align:right; font-weight:600;">{{ $recibo->folio }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0; color:#5a6b80;">Fecha de pago</td>
                        <td style="padding:6px 0; text-align:right; font-weight:600;">{{ $recibo->pago->fecha_pago->translatedFormat('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0; color:#5a6b80;">Monto</td>
                        <td style="padding:6px 0; text-align:right; font-weight:600;">${{ number_format($recibo->pago->monto, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0; color:#5a6b80;">Próximo pago</td>
                        <td style="padding:6px 0; text-align:right; font-weight:600;">{{ $recibo->pago->suscripcion->fecha_proximo_pago->translatedFormat('d M Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
