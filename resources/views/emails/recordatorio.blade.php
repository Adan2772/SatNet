<!doctype html>
<html>
<body style="margin:0; padding:32px 16px; background:#f6f4ee; font-family: Arial, sans-serif; color:#1d2622;">
    <table role="presentation" width="100%" style="max-width:480px; margin:0 auto;">
        <tr>
            <td style="padding-bottom:20px;">
                <span style="display:inline-block; width:32px; height:32px; line-height:32px; text-align:center; background:#0e6e5a; color:#fff; border-radius:8px; font-weight:bold;">S</span>
                <span style="font-weight:bold; font-size:18px; margin-left:8px;">{{ config('app.name') }}</span>
            </td>
        </tr>
        <tr>
            <td style="background:#ffffff; border:1px solid #e1efe8; border-radius:12px; padding:28px;">
                <h1 style="margin:0 0 12px; font-size:20px;">Hola {{ $suscripcion->cliente->nombre }},</h1>
                <p style="margin:0 0 16px; font-size:14px; line-height:1.6; color:#3f4a45;">
                    Tu pago del plan <strong>{{ $suscripcion->plan->nombre }}</strong> venció el
                    <strong>{{ $suscripcion->fecha_proximo_pago->translatedFormat('d \d\e F') }}</strong>.
                    Tienes {{ config('satnet.tolerancia_dias') }} días de tolerancia para ponerte al día
                    sin que se interrumpa tu servicio.
                </p>
                <table role="presentation" width="100%" style="font-size:14px; margin:0 0 20px; border-collapse:collapse;">
                    <tr>
                        <td style="padding:6px 0; color:#8a948d;">Plan</td>
                        <td style="padding:6px 0; text-align:right; font-weight:600;">{{ $suscripcion->plan->nombre }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0; color:#8a948d;">Monto</td>
                        <td style="padding:6px 0; text-align:right; font-weight:600;">${{ number_format($suscripcion->plan->precio, 2) }}</td>
                    </tr>
                </table>
                <p style="margin:0; font-size:13px; color:#8a948d;">
                    Si ya realizaste el pago, ignora este correo — puede que aún no se haya registrado.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
