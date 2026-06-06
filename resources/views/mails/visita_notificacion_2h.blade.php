<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Tu visita comienza pronto! - Check 360</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f7f6;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Check 360</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #1e3a8a; margin-top: 0;">¡Todo listo para tu visita!</h2>
        
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        
        <p>Te recordamos que tu visita en <strong>{{ $restaurante }}</strong> comienza en aproximadamente <strong>2 horas</strong>.</p>
        
        <div style="background-color: #f9fafb; padding: 25px; border-radius: 8px; margin: 25px 0; border: 1px solid #e5e7eb;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; color: #666; width: 40%;">Restaurante:</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>{{ $restaurante }}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 10px; color: #666;">Hora Sugerida:</td>
                    <td style="padding: 10px;"><strong>{{ $hora }}</strong></td>
                </tr>
            </table>
        </div>

        <div style="background-color: #eff6ff; padding: 20px; border-radius: 8px; border-left: 4px solid #3b82f6; margin: 25px 0;">
            <p style="margin: 0; font-weight: bold; color: #1e40af;">📌 Recordatorios importantes:</p>
            <ul style="margin: 10px 0 0 0; padding-left: 20px; color: #1e40af; font-size: 14px;">
                <li>Actúa con total naturalidad, no reveles que eres un Mystery Shopper.</li>
                <li>Presta atención a los detalles de limpieza, atención y tiempos.</li>
                <li>Recuerda tomar fotos discretas si el cuestionario lo requiere.</li>
                <li>Solicita tu boleta o comprobante de pago al finalizar.</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $plataforma }}" style="background-color: #10b981; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Ver Detalles de la Visita</a>
        </div>
        
        <p>¡Que tengas una excelente experiencia evaluando!</p>
        
        <p style="margin-top: 40px;">Saludos cordiales,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
