<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completa tu Encuesta Post-Visita - Check 360</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #28a745 0%, #218838 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Check 360</h1>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #28a745;">¡Hola {{ $nombre }}!</h2>
        
        <p>Esperamos que tu visita al restaurante haya sido exitosa.</p>
        
        <div style="background-color: #fff; padding: 25px; border-radius: 8px; margin: 25px 0; border: 2px solid #28a745;">
            <h3 style="margin-top: 0; color: #28a745; text-align: center;">✅ Visita Realizada</h3>
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
        
        <div style="background-color: #d1ecf1; border: 1px solid #0c5460; padding: 20px; border-radius: 8px; margin: 25px 0;">
            <h3 style="margin-top: 0; color: #0c5460;">📝 Completa tu Encuesta Post-Visita</h3>
            <p style="margin: 0;">
                Ahora es momento de compartir tu experiencia. Por favor, ingresa al sistema y completa la encuesta post-visita con todos los detalles de tu experiencia en el restaurante.
            </p>
        </div>
        
        <p style="text-align: center;">
            <a href="{{ $plataforma }}" style="display: inline-block; background: linear-gradient(135deg, #28a745 0%, #218838 100%); color: white; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                Completar Encuesta Post-Visita
            </a>
        </p>
        
        <div style="background-color: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 8px; margin: 25px 0;">
            <p style="margin: 0; color: #856404; font-size: 14px;">
                <strong>Importante:</strong> Tienes un máximo de 24 horas para completar la encuesta. Si no la completas dentro de este plazo, la visita será marcada como "No completada".
            </p>
        </div>
        
        <p>Tu feedback es muy valioso para nosotros y para mejorar la calidad del servicio.</p>
        
        <p style="margin-top: 30px;">Saludos cordiales,<br><strong>El equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
