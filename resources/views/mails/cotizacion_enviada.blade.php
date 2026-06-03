<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Cotización {{ $cotizacion->numero }}</title>
    <style>
      .btn { padding:10px 16px; border-radius:8px; text-decoration:none; margin-right:8px; display:inline-block }
      .primary { background:#d94855; color:#fff }
      .ghost { background:#f5f5f7; color:#111; border:1px solid #ddd }
    </style>
  </head>
  <body>
    <h2>Hola {{ $cotizacion->cliente->razon_social }},</h2>
    <p>Te enviamos la cotización <strong>{{ $cotizacion->numero }}</strong> por un total de <strong>${{ number_format($cotizacion->total_precio,0,',','.') }}</strong>.</p>
    <p>Puedes revisarla, aceptarla o rechazarla desde los siguientes botones:</p>
    <p>
      <a class="btn primary" href="{{ $urlAceptar }}">Aceptar</a>
      <a class="btn ghost" href="{{ $urlRechazar }}">Rechazar</a>
    </p>
    <p>Saludos,<br/>Equipo</p>
  </body>
</html>

