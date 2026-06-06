<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro No Aprobado - Check 360</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Check 360</h1>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #ef4444;">Registro no aprobado ❌</h2>
        
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        
        <p>Lamentamos informarte que tu solicitud para registrar tu restaurante en <strong>Check 360</strong> no ha sido aprobada en esta ocasión.</p>
        
        <div style="background-color: #fff; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ef4444;">
            <p style="margin: 0;"><strong>Motivo:</strong></p>
            <p style="margin: 10px 0; font-style: italic; color: #666;">"{{ $motivo }}"</p>
        </div>
        
        <p>Si crees que esto es un error o deseas proporcionar más información, no dudes en contactarnos respondiendo a este correo.</p>
        
        <p>Agradecemos tu interés en formar parte de nuestra red.</p>
        
        <p style="margin-top: 30px;">Atentamente,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
