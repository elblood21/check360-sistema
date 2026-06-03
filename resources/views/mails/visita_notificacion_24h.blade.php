<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Visita - Check 360</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Check 360</h1>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #667eea;">¡Hola {{ $nombre }}!</h2>
        
        <p>Te recordamos que tienes una visita programada para <strong>mañana</strong>.</p>
        
        <div style="background-color: #fff; padding: 25px; border-radius: 8px; margin: 25px 0; border: 2px solid #667eea;">
            <h3 style="margin-top: 0; color: #667eea; text-align: center;">📋 Detalles de la Visita</h3>
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
                    <td style="padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                        {{ $hora }}
                    </td>
                </tr>
            </table>
        </div>

        @if($descripcion)
        <div style="background-color: #fff; padding: 20px; border-left: 4px solid #667eea; margin: 20px 0;">
            <p style="margin: 0;"><strong>Descripción del Restaurante:</strong></p>
            <p style="margin: 10px 0 0 0;">{{ $descripcion }}</p>
        </div>
        @endif
        
        <div style="background-color: #fff3cd; border: 1px solid #ffc107; padding: 20px; border-radius: 8px; margin: 25px 0;">
            <h3 style="margin-top: 0; color: #856404;">⚠️ Acción Requerida</h3>
            <p style="margin: 0;">
                <strong>Debes responder la encuesta de expectativas antes de tu visita.</strong>
            </p>
            <p style="margin: 10px 0 0 0;">
                Ingresa al sistema y completa la encuesta para confirmar tu asistencia.
            </p>
        </div>
        
        <p style="text-align: center;">
            <a href="{{ $plataforma }}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                Responder Encuesta Ahora
            </a>
        </p>
        
        <div style="background-color: #f8d7da; border: 1px solid #dc3545; padding: 15px; border-radius: 8px; margin: 25px 0;">
            <p style="margin: 0; color: #721c24; font-size: 14px;">
                <strong>Importante:</strong> Si no respondes la encuesta antes de la fecha de la visita ({{ $fecha }}), la visita será rechazada automáticamente.
            </p>
        </div>
        
        <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
        
        <p style="margin-top: 30px;">Saludos cordiales,<br><strong>El equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
