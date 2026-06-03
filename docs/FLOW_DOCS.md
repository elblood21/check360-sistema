# DocumentaciÃ³n de Flujos: Check 360

## 1. Flujo de Registro y ActivaciÃ³n
### Mistery Shopper
1. El usuario accede a `shopper.check360.cl/registro`.
2. Completa su perfil, experiencia y preferencias.
3. El **Administrador del Sistema** recibe la solicitud.
4. Si el perfil cumple los requisitos, el Administrador aprueba al Shopper.
5. El Shopper recibe sus credenciales por correo.

## 2. Flujo de AsignaciÃ³n de Visitas
1. El **Administrador del Sistema** crea una nueva visita en `sistema.check360.cl`.
2. Selecciona el **Restaurante**, el **Shopper** disponible (segÃºn rotaciÃ³n) y la **Fecha/Hora**.
3. El Shopper es notificado por correo electrÃ³nico.
4. La visita aparece en el Dashboard y menÃº "Visitas" del Shopper como "Pendiente".

## 3. Flujo de AuditorÃ­a (Encuestas)
El proceso de auditorÃ­a se divide en tres fases crÃ­ticas dentro del portal del Shopper:

### Fase A: Expectativas (Entrada)
- **CuÃ¡ndo:** Al menos 24 Horas antes de la visita (o segÃºn configuraciÃ³n).
- **AcciÃ³n:** El Shopper responde preguntas sobre quÃ© espera del local, marca de referencia, etc.
- **Resultado:** La visita cambia de estado a "En Espera de Visita".

### Fase B: La Visita
- **CuÃ¡ndo:** En la fecha y hora pactada.
- **AcciÃ³n:** El Shopper asiste al local de forma encubierta. Al terminar, marca el botÃ³n **"Marcar Visitado"** en su portal.
- **Resultado:** El sistema registra la timestamp de la visita y envÃ­a un recordatorio por correo para la fase final. La visita cambia a "Visita Completada".

### Fase C: Experiencia (Salida)
- **CuÃ¡ndo:** Inmediatamente despuÃ©s de la visita.
- **AcciÃ³n:** El Shopper responde el cuestionario detallado sobre servicio, comida, limpieza y tiempos.
- **Resultado:** La visita cambia a estado "Finalizada".

## 4. Flujo de Resultados
1. Los datos se consolidan automÃ¡ticamente.
2. El **Administrador de Restaurante** puede ver los resultados en su portal (`restaurante.check360.cl`).
3. El dashboard del Restaurante muestra grÃ¡ficos comparativos de Expectativa vs Experiencia.
4. El Administrador puede ver el detalle individual de cada encuesta para tomar acciones de mejora.

## 5. Notificaciones AutomÃ¡ticas
- **Nuevos Usuarios:** Bienvenida y envÃ­o de claves.
- **AsignaciÃ³n:** Aviso de nueva visita.
- **Recordatorios:** 24 horas y 2 horas antes de la visita.
- **Post-Visita:** Link directo a la encuesta de salida una vez marcada como visitada.
