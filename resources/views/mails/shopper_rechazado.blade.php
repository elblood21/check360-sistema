<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Registro - Check 360</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f7f6;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Check 360</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #ef4444; margin-top: 0;">Actualización sobre tu registro</h2>
        
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        
        <p>Gracias por tu interés en formar parte de nuestro equipo de <strong>Mystery Shoppers</strong> en Check 360.</p>
        
        <p>Después de revisar tu solicitud y la información proporcionada, lamentamos informarte que en este momento no hemos podido aprobar tu registro.</p>
        
        @if(isset($motivo) && $motivo)
        <div style="background-color: #fef2f2; padding: 20px; border-left: 4px solid #ef4444; margin: 25px 0; border-radius: 4px;">
            <p style="margin: 0; font-weight: bold; color: #991b1b;">Motivo del rechazo:</p>
            <p style="margin: 10px 0; color: #b91c1c; font-style: italic;">"{{ $motivo }}"</p>
        </div>
        @endif
        
        <p>Agradecemos sinceramente el tiempo dedicado a completar tu perfil. Te invitamos a estar atento a nuestras redes sociales para futuras convocatorias o cambios en nuestros requisitos.</p>
        
        <p>Si tienes alguna pregunta adicional, puedes contactarnos respondiendo a este correo electrónico.</p>
        
        <p style="margin-top: 40px;">Saludos cordiales,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
