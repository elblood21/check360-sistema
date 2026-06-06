<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente Activado - Check 360</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f7f6;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Check 360</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #1e3a8a; margin-top: 0;">{{ $titulo ?? 'Cliente Habilitado' }}</h2>
        
        <p>Hola,</p>
        
        <p>Te informamos que se ha habilitado exitosamente el acceso para el cliente <strong>{{ $cliente->razon_social }}</strong> en la plataforma <strong>Check 360</strong>.</p>
        
        <div style="background-color: #eff6ff; padding: 20px; border-radius: 8px; border-left: 4px solid #3b82f6; margin: 25px 0;">
            <p style="margin: 0; font-size: 15px; color: #1e40af;">
                La cuenta del cliente ahora está operativa y puede acceder a todas las funcionalidades contratadas.
            </p>
        </div>

        <p>Si necesitas realizar configuraciones adicionales o tienes alguna duda, por favor contacta al equipo de soporte.</p>
        
        <p style="margin-top: 40px;">Atentamente,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
