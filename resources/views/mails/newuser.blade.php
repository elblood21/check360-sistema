<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo ?? 'Acceso a Check 360' }}</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f7f6;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Check 360</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #1e3a8a; margin-top: 0;">{{ $titulo ?? 'Bienvenido al Sistema' }}</h2>
        
        <p>Hola <strong>{{ $nombre ?? '' }}</strong>,</p>
        
        <p>Has sido registrado como usuario en la plataforma <strong>Check 360</strong>. A partir de ahora puedes acceder para gestionar tus actividades.</p>
        
        <p>Tus credenciales de acceso son las siguientes:</p>
        
        <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 25px; border-radius: 10px; margin: 25px 0;">
            <h3 style="margin-top: 0; color: white; text-align: center; font-size: 18px;">Información de Acceso</h3>
            <div style="background-color: rgba(255,255,255,0.95); padding: 20px; border-radius: 8px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px 0; border-bottom: 1px solid #eee;">
                            <span style="color: #666; font-size: 13px; display: block;">Usuario / Email</span>
                            <strong style="color: #333; font-size: 15px;">{{ $correo_electronico }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0;">
                            <span style="color: #666; font-size: 13px; display: block;">Contraseña Temporal</span>
                            <div style="background-color: #fef3c7; padding: 8px 15px; border-radius: 4px; display: inline-block; margin-top: 5px; border: 1px solid #fcd34d;">
                                <strong style="font-family: monospace; font-size: 20px; letter-spacing: 2px; color: #92400e;">{{ $pass }}</strong>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div style="background-color: #eff6ff; padding: 15px; border-radius: 8px; border-left: 4px solid #3b82f6; margin-bottom: 25px;">
            <p style="margin: 0; font-size: 14px; color: #1e40af;">
                <strong>Nota:</strong> Te recomendamos cambiar esta contraseña temporal en tu primer inicio de sesión.
            </p>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $plataforma }}" style="background-color: #1e3a8a; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Iniciar Sesión ahora</a>
            <p style="margin-top: 10px; font-size: 12px; color: #666;">Accede en: {{ $plataforma }}</p>
        </div>
        
        <p>Si tienes alguna pregunta o inconveniente para acceder, por favor contacta al administrador del sistema.</p>
        
        <p style="margin-top: 40px;">Atentamente,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
