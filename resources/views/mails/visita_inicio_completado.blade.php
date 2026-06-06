<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionario Inicial Completado - Check 360</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f7f6;">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Check 360</h1>
    </div>
    
    <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #1e3a8a; margin-top: 0;">¡Expectativas Registradas!</h2>
        
        <p>Hola <strong>{{ $nombre }}</strong>,</p>
        
        <p>Has completado con éxito el cuestionario inicial para tu visita en <strong>{{ $restaurante }}</strong>. Aquí tienes los siguientes pasos para completar tu evaluación y obtener tu beneficio:</p>
        
        <div style="background-color: #f9fafb; padding: 25px; border-radius: 10px; margin: 25px 0; border-left: 4px solid #3b82f6;">
            <h3 style="margin-top: 0; color: #1e3a8a; font-size: 18px;">Instrucciones para tu Visita</h3>
            <ol style="padding-left: 20px; color: #4b5563; margin-bottom: 0;">
                <li style="margin-bottom: 15px;">
                    <strong>Asiste en menos de 24 horas:</strong> Debes acudir al restaurante y realizar tu consumo dentro de las próximas 24 horas para que tu evaluación sea válida.
                </li>
                <li style="margin-bottom: 15px;">
                    <strong>Discreción Total:</strong> Realiza tu consumo de forma habitual. <strong>Es fundamental no revelar que estás realizando una evaluación</strong> ni mencionar a Check 360 antes de pagar.
                </li>
                <li style="margin-bottom: 15px;">
                    <strong>Registra y Evalúa:</strong> Una vez termines de consumir (antes de pedir la cuenta), ingresa a la aplicación, presiona <strong>"Registrar Visita"</strong> y completa el cuestionario final de experiencia.
                </li>
                <li>
                    <strong>Obtén tu Beneficio:</strong> Al finalizar, el sistema generará tu <strong>cupón de descuento del {{ $descuento }}%</strong>. Muéstralo al momento de pagar para aplicarlo a tu cuenta.
                </li>
            </ol>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $plataforma }}" style="background-color: #1e3a8a; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Ir a mi Dashboard</a>
        </div>
        
        <p>¡Disfruta tu experiencia y gracias por tu valiosa colaboración!</p>
        
        <p style="margin-top: 40px;">Atentamente,<br><strong>Equipo de Check 360</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un correo automático, por favor no responder.</p>
    </div>
</body>
</html>
