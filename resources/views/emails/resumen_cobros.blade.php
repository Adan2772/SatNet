<!doctype html>
<html>
<body style="margin:0; padding:32px 16px; background:#f3f6fa; font-family: Arial, sans-serif; color:#16233a;">
    <table role="presentation" width="100%" style="max-width:560px; margin:0 auto;">
        <tr>
            <td style="padding-bottom:20px;">
                <span style="display:inline-block; width:32px; height:32px; line-height:32px; text-align:center; background:#0f2e5c; color:#fff; border-radius:8px; font-weight:bold;">S</span>
                <span style="font-weight:bold; font-size:18px; margin-left:8px;">{{ config('app.name') }}</span>
            </td>
        </tr>
        <tr>
            <td style="background:#ffffff; border:1px solid #cfe0f2; border-radius:12px; padding:28px;">
                <h1 style="margin:0 0 4px; font-size:20px;">Resumen de cobros</h1>
                <p style="margin:0 0 24px; font-size:13px; color:#5a6b80;">{{ now()->translatedFormat('d \d\e F \d\e Y') }}</p>

                <h2 style="margin:0 0 10px; font-size:15px; color:#0f2e5c;">
                    Próximos a vencer — {{ $proximas->count() }}
                </h2>
                @if ($proximas->isEmpty())
                    <p style="margin:0 0 20px; font-size:13px; color:#5a6b80;">Nadie vence en los próximos días.</p>
                @else
                    <table role="presentation" width="100%" style="font-size:13px; margin:0 0 24px; border-collapse:collapse;">
                        @foreach ($proximas as $suscripcion)
                            <tr>
                                <td style="padding:5px 0; border-bottom:1px solid #eaf1fa;">{{ $suscripcion->cliente->nombre }}</td>
                                <td style="padding:5px 0; border-bottom:1px solid #eaf1fa; color:#5a6b80;">{{ $suscripcion->plan->nombre }}</td>
                                <td style="padding:5px 0; border-bottom:1px solid #eaf1fa; text-align:right; white-space:nowrap;">
                                    {{ $suscripcion->fecha_proximo_pago->translatedFormat('d M') }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif

                <h2 style="margin:0 0 10px; font-size:15px; color:#b8791a;">
                    En tolerancia — {{ $tolerancia->count() }}
                </h2>
                @if ($tolerancia->isEmpty())
                    <p style="margin:0 0 20px; font-size:13px; color:#5a6b80;">Nadie está en tolerancia hoy.</p>
                @else
                    <table role="presentation" width="100%" style="font-size:13px; margin:0 0 24px; border-collapse:collapse;">
                        @foreach ($tolerancia as $suscripcion)
                            <tr>
                                <td style="padding:5px 0; border-bottom:1px solid #eaf1fa;">{{ $suscripcion->cliente->nombre }}</td>
                                <td style="padding:5px 0; border-bottom:1px solid #eaf1fa; color:#5a6b80;">{{ $suscripcion->plan->nombre }}</td>
                                <td style="padding:5px 0; border-bottom:1px solid #eaf1fa; text-align:right; white-space:nowrap;">
                                    venció {{ $suscripcion->fecha_proximo_pago->translatedFormat('d M') }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif

                <h2 style="margin:0 0 10px; font-size:15px; color:#ae3b34;">
                    Vencidos — {{ $vencidas->count() }}
                </h2>
                @if ($vencidas->isEmpty())
                    <p style="margin:0; font-size:13px; color:#5a6b80;">No hay clientes vencidos.</p>
                @else
                    <table role="presentation" width="100%" style="font-size:13px; margin:0; border-collapse:collapse;">
                        @foreach ($vencidas as $suscripcion)
                            <tr>
                                <td style="padding:5px 0; border-bottom:1px solid #eaf1fa;">{{ $suscripcion->cliente->nombre }}</td>
                                <td style="padding:5px 0; border-bottom:1px solid #eaf1fa; color:#5a6b80;">{{ $suscripcion->plan->nombre }}</td>
                                <td style="padding:5px 0; border-bottom:1px solid #eaf1fa; text-align:right; white-space:nowrap;">
                                    venció {{ $suscripcion->fecha_proximo_pago->translatedFormat('d M') }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
