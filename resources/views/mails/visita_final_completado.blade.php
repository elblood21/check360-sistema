<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación Finalizada - Check 360</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f7f6;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Check 360</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #1e3a8a; margin-top: 0;">¡Evaluación Completada!</h2>
        
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        
        <p>Muchas gracias por completar tu evaluación de experiencia en <strong>{{ $restaurante }}</strong>.</p>
        
        @if($tiene_cupon)
        <div style="background-color: #f0fdf4; border: 2px dashed #10b981; padding: 25px; border-radius: 10px; margin: 25px 0; text-align: center;">
            <h3 style="color: #047857; margin-top: 0; font-size: 20px;">🎉 ¡Aquí tienes tu cupón de descuento!</h3>
            <p style="color: #065f46; margin-bottom: 20px;">Has completado la visita dentro del plazo de 24 horas.</p>
            
            <div style="background-color: #ffffff; display: inline-block; padding: 15px 30px; border-radius: 8px; border: 1px solid #10b981; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <span style="display: block; font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 1px;">Código del Cupón</span>
                <strong style="font-family: monospace; font-size: 28px; color: #1e3a8a; letter-spacing: 3px;">{{ $cupon_codigo }}</strong>
                <span style="display: block; font-size: 18px; color: #10b981; font-weight: bold; margin-top: 5px;">{{ $descuento }}% DE DESCUENTO</span>
            </div>
            
            <div style="margin-top: 25px; padding: 15px; background-color: #ffffff; border-radius: 8px; text-align: left; font-size: 14px; color: #374151;">
                <strong>Instrucciones de canje:</strong>
                <ol style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>Solicita tu cuenta al mesero.</li>
                    <li>Menciona que vienes de parte de <strong>Check 360</strong>.</li>
                    <li>Muestra este código para que apliquen tu descuento.</li>
                </ol>
            </div>
        </div>
        @else
        <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 20px; border-radius: 4px; margin: 25px 0;">
            <p style="margin: 0; color: #92400e; font-weight: bold;">Información sobre tu beneficio:</p>
            <p style="margin: 10px 0 0 0; color: #b45309; font-size: 14px;">Lamentablemente, no se ha generado un cupón de descuento ya que han transcurrido más de 24 horas desde que completaste el cuestionario inicial. Recuerda realizar tus visitas dentro del plazo para obtener tus beneficios.</p>
        </div>
        @endif
        
        <p>Tu feedback es fundamental para ayudar a los restaurantes a mejorar su calidad de servicio.</p>
        
        <p style="margin-top: 40px;">Atentamente,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
