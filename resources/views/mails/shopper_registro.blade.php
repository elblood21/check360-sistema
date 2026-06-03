<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Recibido - Check 360</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Check 360</h1>
    </div>
    
    <div style="background-color: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #667eea;">¡Gracias por tu interés!</h2>
        
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        
        <p>Hemos recibido tu solicitud para convertirte en Mistery Shopper de Check 360.</p>
        
        <div style="background-color: #fff; padding: 25px; border-radius: 8px; margin: 25px 0; border: 2px solid #667eea;">
            <h3 style="margin-top: 0; color: #667eea; text-align: center;">�??? Estado de tu Solicitud</h3>
            <p style="text-align: center; margin: 20px 0;">
                <span style="background-color: #fff3cd; color: #856404; padding: 10px 20px; border-radius: 20px; font-weight: bold; display: inline-block;">
                    ⏳ Pendiente de Aprobación
                </span>
            </p>
            <p style="margin-top: 20px; text-align: center; color: #666;">
                Nuestro equipo está revisando tu información y te notificaremos por correo electrónico cuando tu cuenta sea aprobada.
            </p>
        </div>
        
        <p>Este proceso de revisión puede tomar hasta 48 horas hábiles.</p>
        
        @if(isset($password))
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 10px; margin: 30px 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; color: white; text-align: center; font-size: 20px;">�??� Tus Credenciales de Acceso</h3>
            <div style="background-color: rgba(255,255,255,0.95); padding: 25px; border-radius: 8px; margin-top: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 15px 10px; border-bottom: 2px solid #e0e0e0;">
                            <span style="color: #666; font-size: 14px; display: block; margin-bottom: 5px;">�??� Usuario</span>
                            <strong style="color: #333; font-size: 16px;">{{ $correo_electronico }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 15px 10px;">
                            <span style="color: #666; font-size: 14px; display: block; margin-bottom: 5px;">�??? Contraseña Temporal</span>
                            <div style="background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%); padding: 12px 20px; border-radius: 6px; display: inline-block; margin-top: 5px;">
                                <strong style="font-family: 'Courier New', monospace; font-size: 24px; letter-spacing: 3px; color: #2d3436;">{{ $password }}</strong>
                            </div>
                        </td>
                    </tr>
                </table>
                <p style="margin-top: 20px; margin-bottom: 0; font-size: 13px; color: #666; text-align: center; padding: 15px; background-color: #fff3cd; border-radius: 6px; border-left: 4px solid #ffc107;">
                    �?�️ <strong>Importante:</strong> Guarda estas credenciales. Podrás iniciar sesión una vez que tu cuenta sea aprobada. Te recomendamos cambiar tu contraseña al iniciar sesión por primera vez.
                </p>
            </div>
        </div>
        @else
        <div style="background-color: #e3f2fd; padding: 20px; border-left: 4px solid #2196f3; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; color: #1565c0;">
                <strong>�??� Importante:</strong> Una vez que tu cuenta sea aprobada, recibirás un correo electrónico con tus credenciales de acceso (usuario y contraseña) para que puedas iniciar sesión en la plataforma.
            </p>
        </div>
        @endif
        
        <div style="background-color: #fff; padding: 20px; border-left: 4px solid #667eea; margin: 20px 0;">
            <p style="margin: 0;"><strong>¿Qué sigue?</strong></p>
            <ul style="margin: 10px 0;">
                <li>Revisaremos tu información</li>
                <li>Verificaremos tu perfil</li>
                <li>Te enviaremos un correo cuando sea aprobada</li>
                <li>Podrás iniciar sesión en <strong>shopper.check360.cl</strong></li>
            </ul>
        </div>
        
        <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
        
        <p style="margin-top: 30px;">Saludos cordiales,<br><strong>El equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
