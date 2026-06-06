<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo ?? 'Cliente Aprobado - Check 360' }}</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f7f6;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Check 360</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #10b981; margin-top: 0;">{{ $titulo ?? 'Cliente Aprobado' }}</h2>
        
        <p>Hola,</p>
        
        <p>El cliente <strong>{{ $nombre }}</strong> ha sido aprobado satisfactoriamente en la plataforma <strong>Check 360</strong>.</p>
        
        <div style="background-color: #f0fdf4; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981; margin: 25px 0;">
            <p style="margin: 0; font-size: 15px; color: #166534;">
                La cuenta ya se encuentra activa y puede comenzar a operar en el sistema.
            </p>
        </div>

        <p>Puedes ingresar a la plataforma utilizando las credenciales previamente configuradas o enviadas al correo de contacto.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $plataforma }}" style="background-color: #1e3a8a; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Acceder a la Plataforma</a>
        </div>
        
        <p>Si tienes alguna pregunta, no dudes en contactar con nuestro soporte técnico.</p>
        
        <p style="margin-top: 40px;">Atentamente,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
