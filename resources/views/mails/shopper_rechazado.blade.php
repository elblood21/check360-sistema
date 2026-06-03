<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro No Aprobado - Check 360</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Check 360</h1>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #dc3545;">Actualización sobre tu registro</h2>
        
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        
        <p>Gracias por tu interés en ser parte de nuestro equipo de Mistery Shoppers.</p>
        
        <p>Después de revisar tu solicitud, lamentamos informarte que en este momento no podemos aprobar tu registro.</p>
        
        @if(isset($motivo) && $motivo)
        <div style="background-color: #fff; padding: 20px; border-left: 4px solid #dc3545; margin: 20px 0;">
            <p style="margin: 0;"><strong>Motivo:</strong></p>
            <p style="margin: 10px 0;">{{ $motivo }}</p>
        </div>
        @endif
        
        <p>Puedes volver a aplicar en el futuro cuando cumplas con los requisitos necesarios.</p>
        
        <p>Si tienes alguna pregunta sobre esta decisión, no dudes en contactarnos.</p>
        
        <p style="margin-top: 30px;">Saludos cordiales,<br><strong>El equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
