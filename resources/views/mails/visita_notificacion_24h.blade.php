<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Visita - Check 360</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f7f6;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Check 360</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #1e3a8a; margin-top: 0;">¡Hola {{ $nombre }}!</h2>
        
        <p>Te recordamos que tienes una visita de <strong>Mystery Shopper</strong> programada para <strong>mañana</strong>.</p>
        
        <div style="background-color: #f9fafb; padding: 25px; border-radius: 8px; margin: 25px 0; border: 1px solid #e5e7eb;">
            <h3 style="margin-top: 0; color: #1e3a8a; text-align: center; font-size: 18px;">Detalles de la Visita</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; color: #666; width: 40%;">Restaurante:</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>{{ $restaurante }}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; color: #666;">Fecha:</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>{{ $fecha }}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 10px; color: #666;">Hora Sugerida:</td>
                    <td style="padding: 10px;"><strong>{{ $hora }}</strong></td>
                </tr>
            </table>
        </div>

        @if($descripcion)
        <div style="background-color: #fff; padding: 20px; border-left: 4px solid #3b82f6; margin: 25px 0; background-color: #eff6ff;">
            <p style="margin: 0; font-weight: bold; color: #1e40af;">Nota del Restaurante:</p>
            <p style="margin: 10px 0 0 0; color: #1e40af; font-size: 14px;">{{ $descripcion }}</p>
        </div>
        @endif
        
        <div style="background-color: #fffbeb; border: 1px solid #fcd34d; padding: 20px; border-radius: 8px; margin: 25px 0;">
            <h3 style="margin-top: 0; color: #92400e; font-size: 16px;">⚠️ Acción Requerida</h3>
            <p style="margin: 0; font-size: 14px; color: #92400e;">
                Recuerda que debes <strong>responder la encuesta de expectativas</strong> antes de realizar tu visita. Si no la completas antes de la fecha programada, la visita podría ser cancelada automáticamente.
            </p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $plataforma }}" style="background-color: #1e3a8a; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Completar Encuesta Ahora</a>
        </div>
        
        <p>Si tienes algún inconveniente o no puedes asistir, por favor avísanos a la brevedad posible.</p>
        
        <p style="margin-top: 40px;">Saludos cordiales,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
