<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Aprobada - Check 360</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Check 360</h1>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #28a745;">¬°Tu cuenta ha sido aprobada! ???</h2>
        
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        
        <p>¬°Excelentes noticias! Tu cuenta de Mistery Shopper ha sido <strong>aprobada y activada</strong>.</p>
        
        <p>Ya puedes acceder a la plataforma. A continuaci√≥n encontrar√°s tus credenciales de acceso:</p>
        
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 10px; margin: 30px 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; color: white; text-align: center; font-size: 20px;">??ê Tus Credenciales de Acceso</h3>
            <div style="background-color: rgba(255,255,255,0.95); padding: 25px; border-radius: 8px; margin-top: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 15px 10px; border-bottom: 2px solid #e0e0e0;">
                            <span style="color: #666; font-size: 14px; display: block; margin-bottom: 5px;">??§ Usuario</span>
                            <strong style="color: #333; font-size: 16px;">{{ $correo_electronico }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 15px 10px;">
                            <span style="color: #666; font-size: 14px; display: block; margin-bottom: 5px;">??? Contrase√±a</span>
                            <div style="background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%); padding: 12px 20px; border-radius: 6px; display: inline-block; margin-top: 5px;">
                                <strong style="font-family: 'Courier New', monospace; font-size: 24px; letter-spacing: 3px; color: #2d3436;">{{ $password }}</strong>
                            </div>
                        </td>
                    </tr>
                </table>
                <p style="margin-top: 20px; margin-bottom: 0; font-size: 13px; color: #666; text-align: center; padding: 15px; background-color: #fff3cd; border-radius: 6px; border-left: 4px solid #ffc107;">
                    ‚?†Ô∏è <strong>Importante:</strong> Te recomendamos cambiar tu contrase√±a al iniciar sesi√≥n por primera vez.
                </p>
            </div>
        </div>
        
        <div style="background-color: #fff; padding: 20px; border-radius: 5px; margin: 20px 0; border: 2px solid #28a745;">
            <p style="margin: 0; text-align: center;">
                <strong>Accede a tu cuenta en:</strong><br>
                <a href="{{ $plataforma }}" style="color: #667eea; font-size: 18px; text-decoration: none; font-weight: bold;">{{ $plataforma }}</a>
            </p>
        </div>
        
        <div style="background-color: #fff; padding: 20px; border-left: 4px solid #667eea; margin: 20px 0;">
            <p style="margin: 0;"><strong>Pr√≥ximos pasos:</strong></p>
            <ul style="margin: 10px 0;">
                <li>Inicia sesi√≥n en la plataforma</li>
                <li>Completa tu perfil</li>
                <li>Revisa las visitas asignadas</li>
                <li>Comienza a realizar evaluaciones</li>
            </ul>
        </div>
        
        <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.</p>
        
        <p style="margin-top: 30px;">¬°Bienvenido al equipo!<br><strong>Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>Este es un correo autom√°tico, por favor no responder.</p>
    </div>
</body>
</html>
