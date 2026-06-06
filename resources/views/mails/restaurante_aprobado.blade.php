<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Aprobada - Check 360</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Check 360</h1>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #10b981;">¡Tu cuenta ha sido aprobada! 🚀</h2>
        
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        
        <p>¡Excelentes noticias! La solicitud de tu restaurante ha sido <strong>aprobada</strong>. Tu cuenta ya se encuentra activa.</p>
        
        <p>A continuación encontrarás tus credenciales para acceder a la plataforma de administración:</p>
        
        <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; border-radius: 10px; margin: 30px 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; color: white; text-align: center; font-size: 20px;">🔑 Tus Credenciales de Acceso</h3>
            <div style="background-color: rgba(255,255,255,0.95); padding: 25px; border-radius: 8px; margin-top: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 15px 10px; border-bottom: 2px solid #e0e0e0;">
                            <span style="color: #666; font-size: 14px; display: block; margin-bottom: 5px;">📧 Usuario / Email</span>
                            <strong style="color: #333; font-size: 16px;">{{ $correo_electronico }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 15px 10px;">
                            <span style="color: #666; font-size: 14px; display: block; margin-bottom: 5px;">🔒 Contraseña Temporal</span>
                            <div style="background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%); padding: 12px 20px; border-radius: 6px; display: inline-block; margin-top: 5px;">
                                <strong style="font-family: 'Courier New', monospace; font-size: 24px; letter-spacing: 3px; color: #2d3436;">{{ $pass }}</strong>
                            </div>
                        </td>
                    </tr>
                </table>
                <p style="margin-top: 20px; margin-bottom: 0; font-size: 13px; color: #666; text-align: center; padding: 15px; background-color: #fff3cd; border-radius: 6px; border-left: 4px solid #ffc107;">
                    ⚠️ <strong>Seguridad:</strong> Te recomendamos cambiar tu contraseña al iniciar sesión por primera vez.
                </p>
            </div>
        </div>
        
        <div style="background-color: #fff; padding: 20px; border-radius: 5px; margin: 20px 0; border: 2px solid #10b981;">
            <p style="margin: 0; text-align: center;">
                <strong>Accede a tu panel en:</strong><br>
                <a href="{{ $plataforma }}" style="color: #1e3a8a; font-size: 18px; text-decoration: none; font-weight: bold;">{{ $plataforma }}</a>
            </p>
        </div>
        
        <p>Desde tu panel podrás gestionar la información de tu restaurante, ver las evaluaciones de los Mystery Shoppers y canjear los cupones de descuento.</p>
        
        <p>¡Bienvenido a Check 360!</p>
        
        <p style="margin-top: 30px;">Atentamente,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
