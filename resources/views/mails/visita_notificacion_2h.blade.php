<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio Urgente - Check 360</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Check 360</h1>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #dc3545;">⚠️ ¡Recordatorio Urgente, {{ $nombre }}!</h2>
        
        <p>Tu visita está programada para <strong>dentro de 2 horas</strong> y aún no has respondido la encuesta de expectativas.</p>
        
        <div style="background-color: #fff; padding: 25px; border-radius: 8px; margin: 25px 0; border: 3px solid #dc3545;">
            <h3 style="margin-top: 0; color: #dc3545; text-align: center;">🚨 Detalles de la Visita</h3>
            <table style="width: 100%; margin: 15px 0;">
                <tr>
                    <td style="padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                        <strong>Restaurante:</strong>
                    </td>
                    <td style="padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                        {{ $restaurante }}
                    </td>
                </tr>
                <tr><td colspan="2" style="height: 10px;"></td></tr>
                <tr>
                    <td style="padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                        <strong>Fecha:</strong>
                    </td>
                    <td style="padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                        {{ $fecha }}
                    </td>
                </tr>
                <tr><td colspan="2" style="height: 10px;"></td></tr>
                <tr>
                    <td style="padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                        <strong>Hora:</strong>
                    </td>
                    <td style="padding: 10px; background-color: #d4edda; border-radius: 5px; font-weight: bold; color: #155724; font-size: 18px;">
                        {{ $hora }}
                    </td>
                </tr>
            </table>
        </div>
        
        <div style="background-color: #f8d7da; border: 2px solid #dc3545; padding: 25px; border-radius: 8px; margin: 25px 0; text-align: center;">
            <h3 style="margin-top: 0; color: #721c24;">⏰ ¡ÚLTIMA OPORTUNIDAD!</h3>
            <p style="margin: 0; font-size: 16px; font-weight: bold;">
                Debes responder la encuesta de expectativas <span style="color: #dc3545;">ANTES de la hora de tu visita</span>.
            </p>
            <p style="margin: 10px 0 0 0; font-size: 14px;">
                Si no respondes antes de {{ $fecha }} a las {{ $hora }}, la visita será rechazada automáticamente.
            </p>
        </div>
        
        <p style="text-align: center;">
            <a href="{{ $plataforma }}" style="display: inline-block; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; padding: 15px 30px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 18px;">
                🚀 Responder Encuesta AHORA
            </a>
        </p>
        
        <p style="text-align: center; margin-top: 30px;">Si tienes alguna pregunta urgente, contáctanos inmediatamente.</p>
        
        <p style="margin-top: 30px;">Saludos cordiales,<br><strong>El equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
