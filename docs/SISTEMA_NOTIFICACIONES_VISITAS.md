# Sistema de Notificaciones de Visitas - Check 360

## Resumen de Cambios Implementados

### 1. Campo de Hora de Asignación Restaurado

**Archivos modificados:**
- `resources/views/visitas/nuevo.blade.php`
- `resources/views/visitas/lista.blade.php`
- `app/Http/Controllers/VisitaController.php`

**Cambios:**
- Se agregó de vuelta el campo `hora_asignacion` en el formulario de crear/editar visitas
- El campo es obligatorio
- Se muestra en la lista de visitas en formato `HH:mm`
- Se calcula automáticamente el `tipo_horario` basado en la hora

---

### 2. Campos de Notificación en Base de Datos

**Migración:** `2026_01_06_211508_add_notificacion_fields_to_visitas_table.php`

**Nuevos campos en tabla `visitas`:**
- `notificado_24horas` (boolean, default 0)
- `notificado_24horas_at` (timestamp, nullable)
- `notificado_2horas` (boolean, default 0)
- `notificado_2horas_at` (timestamp, nullable)
- `notificado_post` (boolean, default 0)
- `notificado_post_at` (timestamp, nullable)

**Modelo actualizado:** `app/Models/Visita.php`
- Campos agregados al `$fillable`

---

### 3. Emails de Notificación

**Archivos creados:**

#### a) `resources/views/mails/visita_notificacion_24h.blade.php`
- **Asunto:** "Recordatorio: Visita programada para mañana"
- **Cuándo se envía:** 24 horas antes de la visita
- **Contenido:**
  - Detalles de la visita (restaurante, fecha, hora)
  - Descripción del restaurante
  - Recordatorio para responder encuesta de expectativas
  - Advertencia: si no responde antes de la fecha, la visita será rechazada

#### b) `resources/views/mails/visita_notificacion_2h.blade.php`
- **Asunto:** "⚠️ URGENTE: Tu visita es en 2 horas"
- **Cuándo se envía:** 2 horas antes de la visita (si aún no respondió pre-encuesta)
- **Contenido:**
  - Alerta urgente
  - Detalles de la visita
  - Última oportunidad para responder encuesta
  - Advertencia de rechazo automático

#### c) `resources/views/mails/visita_notificacion_post.blade.php`
- **Asunto:** "Completa tu encuesta post-visita"
- **Cuándo se envía:** Después de que el shopper marca que ya visitó (responde pre-encuesta)
- **Contenido:**
  - Confirmación de visita realizada
  - Solicitud para completar encuesta post-visita
  - Plazo: 24 horas máximo
  - Advertencia: si no completa en 24h, se marca como "No completada"

---

### 4. Command de Verificación de Notificaciones

**Archivo:** `app/Console/Commands/VerificarNotificacionesVisitas.php`

**Comando:** `php artisan visitas:verificar-notificaciones`

**Funciones:**

#### a) `verificarNotificaciones24Horas()`
- Busca visitas pendientes (estado_id = 1)
- Que estén a menos de 24 horas
- Que no hayan sido notificadas (`notificado_24horas = 0`)
- Que no hayan respondido la pre-encuesta
- **Acción:** Envía email y marca `notificado_24horas = 1`

#### b) `verificarNotificaciones2Horas()`
- Busca visitas pendientes
- Que ya fueron notificadas 24h antes
- Que estén a menos de 2 horas
- Que no hayan sido notificadas 2h (`notificado_2horas = 0`)
- Que no hayan respondido la pre-encuesta
- **Acción:** Envía email urgente y marca `notificado_2horas = 1`

#### c) `verificarVisitasVencidas()`
- **Para visitas pendientes:**
  - Si ya pasó la fecha/hora de la visita
  - Y no respondió la pre-encuesta
  - **Acción:** Cambia estado a "Rechazada" (estado_id = 5) con motivo automático

- **Para visitas en proceso:**
  - Si ya pasaron más de 24 horas desde la visita
  - Y no respondió la post-encuesta
  - **Acción:** Cambia estado a "No completada" (estado_id = 4)

---

### 5. Lógica de Responder Encuestas

**Archivo modificado:** `app/Http/Controllers/VisitaController.php`

**Método:** `guardarEncuestaEntrada()`
- Después de guardar respuestas de pre-encuesta
- Cambia estado a "En proceso" (estado_id = 2)
- **Nuevo:** Envía email de notificación post-encuesta
- Marca `notificado_post = 1` y `notificado_post_at = now()`

**Nuevo método:** `enviarEmailNotificacionPost($visita)`
- Envía el email con plantilla `visita_notificacion_post`
- Incluye detalles de la visita
- Link a la plataforma shopper

---

### 6. Configuración de Cron

**Archivo:** `routes/console.php`

**Línea agregada:**
```php
Schedule::command('visitas:verificar-notificaciones')->everyTenMinutes();
```

**Cómo configurar en el servidor:**

En el crontab del servidor, agregar:
```bash
* * * * * cd /path/to/check360 && php artisan schedule:run >> /dev/null 2>&1
```

Esto ejecutará el scheduler de Laravel cada minuto, y Laravel se encargará de ejecutar el command cada 10 minutos.

---

## Flujo Completo del Sistema

### 1. Creación de Visita
1. Se crea una visita con fecha y hora
2. Si la visita está a menos de 24 horas, se marca para notificación inmediata
3. Estado inicial: "Pendiente" (estado_id = 1)

### 2. Notificaciones Automáticas (cada 10 minutos)
1. **24 horas antes:**
   - Command verifica visitas a 24h
   - Envía email recordatorio
   - Marca `notificado_24horas = 1`

2. **2 horas antes (si no respondió):**
   - Command verifica visitas a 2h
   - Envía email urgente
   - Marca `notificado_2horas = 1`

3. **Si pasa la fecha sin responder:**
   - Command rechaza automáticamente la visita
   - Estado cambia a "Rechazada" (estado_id = 5)
   - Motivo: "No respondió la encuesta de expectativas antes de la fecha de la visita"

### 3. Shopper Responde Pre-Encuesta
1. Shopper ingresa al sistema
2. Responde encuesta de expectativas
3. Estado cambia a "En proceso" (estado_id = 2)
4. **Se envía automáticamente email de post-encuesta**
5. Marca `notificado_post = 1`

### 4. Después de la Visita
1. Shopper puede responder post-encuesta inmediatamente o dentro de 24h
2. Si responde: Estado cambia a "Completada" (estado_id = 3)
3. **Si no responde en 24h:**
   - Command marca como "No completada" (estado_id = 4)

---

## Estados de Visita

| ID | Nombre | Descripción |
|----|--------|-------------|
| 1 | Pendiente | Visita creada, esperando pre-encuesta |
| 2 | En proceso | Pre-encuesta respondida, esperando visita y post-encuesta |
| 3 | Completada | Ambas encuestas completadas |
| 4 | No completada | Post-encuesta no respondida en 24h |
| 5 | Rechazada | Pre-encuesta no respondida antes de la fecha |

---

## Validaciones de Tiempo

### Pre-Encuesta
- Puede responderse 24 horas antes de la fecha de asignación
- Si no se responde antes de la fecha/hora de la visita, se rechaza automáticamente

### Post-Encuesta
- Puede responderse inmediatamente después de marcar visita como realizada
- Máximo 24 horas después de la fecha/hora de la visita
- Si no se completa en 24h, se marca como "No completada"

---

## Pruebas Recomendadas

### 1. Crear visita con menos de 24 horas
- Verificar que se envíe email inmediatamente al ejecutar el command

### 2. Crear visita con más de 24 horas
- Esperar a que el cron ejecute (o ejecutar manualmente)
- Verificar email 24h antes

### 3. No responder pre-encuesta
- Verificar email de 2h antes
- Verificar rechazo automático después de la fecha

### 4. Responder pre-encuesta
- Verificar que se envíe email de post-encuesta
- Verificar cambio de estado a "En proceso"

### 5. No responder post-encuesta
- Esperar 24h después de la visita
- Verificar que se marque como "No completada"

---

## Comandos Útiles

### Ejecutar manualmente el command
```bash
php artisan visitas:verificar-notificaciones
```

### Ver logs del command
```bash
tail -f storage/logs/laravel.log
```

### Limpiar caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Ejecutar migraciones
```bash
php artisan migrate
```

---

## Notas Importantes

1. **URLs de plataforma:** Los emails usan `https://shopper.check360.cl` - ajustar según el dominio real

2. **Zona horaria:** Verificar que la zona horaria en `config/app.php` sea correcta para Chile

3. **Cron:** Asegurarse de que el cron esté configurado en el servidor para que el command se ejecute cada 10 minutos

4. **Emails:** Verificar configuración de email en `.env` (MAIL_MAILER, MAIL_HOST, etc.)

5. **Testing:** Probar todos los flujos antes de producción

---

## Archivos Modificados/Creados

### Creados:
- `database/migrations/2026_01_06_211508_add_notificacion_fields_to_visitas_table.php`
- `app/Console/Commands/VerificarNotificacionesVisitas.php`
- `resources/views/mails/visita_notificacion_24h.blade.php`
- `resources/views/mails/visita_notificacion_2h.blade.php`
- `resources/views/mails/visita_notificacion_post.blade.php`
- `docs/SISTEMA_NOTIFICACIONES_VISITAS.md`

### Modificados:
- `app/Models/Visita.php`
- `app/Http/Controllers/VisitaController.php`
- `resources/views/visitas/nuevo.blade.php`
- `resources/views/visitas/lista.blade.php`
- `routes/console.php`

---

## Deployment

1. Subir archivos al servidor
2. Ejecutar migraciones: `php artisan migrate`
3. Limpiar caches: `php artisan config:clear && php artisan cache:clear && php artisan view:clear`
4. Configurar cron en el servidor
5. Probar el command manualmente: `php artisan visitas:verificar-notificaciones`
6. Verificar que los emails se envíen correctamente

---

Fecha de implementación: 06/01/2026
