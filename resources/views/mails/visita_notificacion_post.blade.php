<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Visita realizada! Completa tu evaluación - Check 360</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f7f6;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Check 360</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #1e3a8a; margin-top: 0;">¡Excelente trabajo!</h2>
        
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        
        <p>Hemos registrado que has completado tu visita en <strong>{{ $restaurante }}</strong>. ¡Ahora solo falta el último paso!</p>
        
        <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 20px; border-radius: 8px; margin: 25px 0;">
            <p style="margin: 0; font-size: 15px; color: #166534; font-weight: bold;">
                Completa el cuestionario de experiencia para obtener tu cupón de beneficio.
            </p>
            <p style="margin: 10px 0 0 0; color: #166534; font-size: 14px;">
                Es muy importante que completes la evaluación mientras los detalles de tu experiencia están frescos en tu memoria.
            </p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $plataforma }}" style="background-color: #1e3a8a; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Completar Evaluación Final</a>
        </div>
        
        <div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; margin-bottom: 25px;">
            <p style="margin: 0; font-size: 13px; color: #666;">
                <strong>Recuerda:</strong> Al finalizar la encuesta, el sistema generará automáticamente tu cupón de descuento o beneficio para tu próxima visita.
            </p>
        </div>
        
        <p>¡Muchas gracias por tu valiosa colaboración!</p>
        
        <p style="margin-top: 40px;">Saludos cordiales,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
