<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Check 360</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Check 360</h1>
        <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 16px;">Mistery Shopper</p>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #667eea;">Â¡Bienvenido al equipo! ğ???</h2>
        
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        
        <p>Te damos la bienvenida a Check 360 como <strong>Mistery Shopper</strong>. Tu cuenta ha sido creada y estÃ¡ completamente activa.</p>
        
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 10px; margin: 30px 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; color: white; text-align: center; font-size: 20px;">ğ?? Credenciales de Acceso</h3>
            <div style="background-color: rgba(255,255,255,0.95); padding: 25px; border-radius: 8px; margin-top: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 15px 10px; border-bottom: 2px solid #e0e0e0;">
                            <span style="color: #666; font-size: 14px; display: block; margin-bottom: 5px;">ğ??¤ Usuario</span>
                            <strong style="color: #333; font-size: 16px;">{{ $correo_electronico }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 15px 10px;">
                            <span style="color: #666; font-size: 14px; display: block; margin-bottom: 5px;">ğ??? ContraseÃ±a</span>
                            <div style="background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%); padding: 12px 20px; border-radius: 6px; display: inline-block; margin-top: 5px;">
                                <strong style="font-family: 'Courier New', monospace; font-size: 24px; letter-spacing: 3px; color: #2d3436;">{{ $pass }}</strong>
                            </div>
                        </td>
                    </tr>
                </table>
                <p style="margin-top: 20px; margin-bottom: 0; font-size: 13px; color: #666; text-align: center; padding: 15px; background-color: #fff3cd; border-radius: 6px; border-left: 4px solid #ffc107;">
                    â? ï¸ <strong>Importante:</strong> Te recomendamos cambiar tu contraseÃ±a al iniciar sesiÃ³n por primera vez.
                </p>
            </div>
        </div>
        
        <div style="background-color: #fff; padding: 25px; border-radius: 8px; margin: 25px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3 style="color: #667eea; margin-top: 0;">ğ??? Accede a la Plataforma</h3>
            <p style="text-align: center; margin: 20px 0;">
                <a href="{{ $plataforma }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 25px; font-weight: bold; display: inline-block; box-shadow: 0 4px 6px rgba(102, 126, 234, 0.4);">
                    Iniciar SesiÃ³n Ahora
                </a>
            </p>
            <p style="text-align: center; margin-top: 15px; font-size: 14px; color: #666;">
                O copia este enlace en tu navegador:<br>
                <a href="{{ $plataforma }}" style="color: #667eea; word-break: break-all;">{{ $plataforma }}</a>
            </p>
        </div>
        
        <div style="background-color: #fff; padding: 25px; border-left: 4px solid #667eea; margin: 25px 0; border-radius: 0 8px 8px 0;">
            <h3 style="color: #667eea; margin-top: 0;">ğ??? Primeros Pasos</h3>
            <ol style="margin: 10px 0; padding-left: 20px; color: #555;">
                <li style="margin-bottom: 10px;">Inicia sesiÃ³n con tus credenciales</li>
                <li style="margin-bottom: 10px;">Completa tu perfil personal</li>
                <li style="margin-bottom: 10px;">Revisa las visitas asignadas en tu dashboard</li>
                <li style="margin-bottom: 10px;">FamiliarÃ­zate con el sistema de encuestas</li>
                <li style="margin-bottom: 10px;">Â¡Comienza a realizar evaluaciones!</li>
            </ol>
        </div>

        <div style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); padding: 20px; border-radius: 8px; margin: 25px 0; border-left: 4px solid #4caf50;">
            <h3 style="color: #2e7d32; margin-top: 0;">ğ??¡ Consejos para un buen inicio</h3>
            <ul style="margin: 10px 0; padding-left: 20px; color: #33691e;">
                <li style="margin-bottom: 8px;">Lee cuidadosamente las instrucciones de cada visita</li>
                <li style="margin-bottom: 8px;">Completa las encuestas lo mÃ¡s detallado posible</li>
                <li style="margin-bottom: 8px;">MantÃ©n actualizada tu disponibilidad</li>
                <li style="margin-bottom: 8px;">Comunica cualquier inconveniente de inmediato</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0; padding: 20px; background-color: #f5f5f5; border-radius: 8px;">
            <p style="margin: 0; color: #666; font-size: 14px;">Â¿Necesitas ayuda?</p>
            <p style="margin: 10px 0 0 0; color: #667eea; font-weight: bold;">Estamos aquÃ­ para apoyarte</p>
        </div>
        
        <p style="margin-top: 30px; color: #666;">Â¡Estamos emocionados de tenerte en el equipo!<br><strong style="color: #667eea;">Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px; padding: 20px;">
        <p style="margin: 5px 0;">Este es un correo automÃ¡tico, por favor no responder.</p>
        <p style="margin: 5px 0;">Â© 2025 Check 360. Todos los derechos reservados.</p>
    </div>
</body>
</html>
