<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Recibido - Check 360</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Check 360</h1>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #1e3a8a;">¡Hemos recibido tu registro! 🍽️</h2>
        
        <p>Hola <strong>{{ $data['nombre'] }}</strong>,</p>
        
        <p>Gracias por registrar tu restaurante en <strong>Check 360</strong>. Hemos recibido tu solicitud correctamente.</p>
        
        <div style="background-color: #fff; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #f59e0b;">
            <p style="margin: 0;"><strong>Estado de tu registro:</strong></p>
            <p style="margin: 10px 0; font-weight: bold; color: #d97706;">Pendiente de aprobación</p>
            <p style="margin: 0; font-size: 0.9rem;">Nuestro equipo revisará la información de tu restaurante en las próximas horas. Una vez aprobado, recibirás un nuevo correo electrónico con tus <strong>datos de acceso</strong> a la plataforma.</p>
        </div>
        
        <p>En Check 360 te ayudamos a mejorar la experiencia de tus clientes a través de evaluaciones reales de Mystery Shoppers.</p>
        
        <p>Si tienes alguna pregunta mientras tanto, puedes contactarnos respondiendo a este correo.</p>
        
        <p style="margin-top: 30px;">Atentamente,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder a esta dirección.</p>
    </div>
</body>
</html>
