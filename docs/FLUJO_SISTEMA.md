# Flujo del Sistema Check 360

## Subdominios y Accesos

El sistema Check 360 funciona con 3 subdominios diferentes:

### 1. **sistema.check360.cl** (Subdominio Sistema)
- **Usuarios**: Administradores del sistema
- **Perfiles permitidos**: Perfil 1 y 2 (definidos en tabla `perfiles`)
- **Funcionalidades**:
  - GestiÃ³n completa de restaurantes
  - GestiÃ³n completa de Mistery Shoppers (crear, aprobar, rechazar)
  - GestiÃ³n de visitas
  - GestiÃ³n de encuestas
  - GestiÃ³n de tipos de cocina
  - GestiÃ³n de usuarios del sistema
  - Dashboard con estadÃ­sticas generales

### 2. **restaurante.check360.cl** (Subdominio Restaurante)
- **Usuarios**: Usuarios de restaurantes
- **Tabla**: `restaurante_users`
- **Funcionalidades**:
  - Ver visitas asignadas a su restaurante
  - Ver resultados de evaluaciones
  - Dashboard con estadÃ­sticas del restaurante

### 3. **shopper.check360.cl** (Subdominio Shopper)
- **Usuarios**: Mistery Shoppers
- **Tabla**: `mistery_shoppers`
- **Funcionalidades**:
  - Registro pÃºblico (con aprobaciÃ³n pendiente)
  - Ver visitas asignadas
  - Responder encuestas de entrada y salida
  - Dashboard con sus visitas

---

## Flujo de Registro y AprobaciÃ³n de Mistery Shoppers

### OpciÃ³n A: Registro PÃºblico (Shopper se registra)

1. **Mistery Shopper visita**: `shopper.check360.cl/registro`
2. **Completa formulario** con:
   - Nombre completo (*)
   - Correo electrÃ³nico (*)
   - ContraseÃ±a (*)
   - TelÃ©fono
   - Observaciones/experiencia
3. **Sistema crea registro**:
   - `estado = 0` (inactivo)
   - `aprobado = 0` (pendiente de aprobaciÃ³n)
   - `password` = hash de la contraseÃ±a ingresada
4. **Se envÃ­a email** de confirmaciÃ³n de registro recibido
5. **Shopper NO puede iniciar sesiÃ³n** hasta ser aprobado

### OpciÃ³n B: CreaciÃ³n desde Sistema (Admin crea)

1. **Admin accede a**: `sistema.check360.cl/shoppers/nuevo`
2. **Completa formulario** con datos del shopper
3. **Sistema crea registro**:
   - `estado = 1` (activo)
   - `aprobado = 1` (aprobado automÃ¡ticamente)
   - `aprobado_por` = ID del usuario admin
   - `aprobado_at` = fecha actual
   - `password` = contraseÃ±a temporal generada
4. **Se envÃ­a email** con credenciales de acceso
5. **Shopper puede iniciar sesiÃ³n** inmediatamente

---

## Proceso de AprobaciÃ³n (Solo para registros pÃºblicos)

### Desde el Panel de AdministraciÃ³n

1. **Admin accede a**: `sistema.check360.cl/shoppers`
2. **Visualiza lista** con columna "AprobaciÃ³n":
   - ðŸŸ¡ **Pendiente**: Requiere acciÃ³n
   - ðŸŸ¢ **Aprobado**: Ya activo
3. **Para shoppers pendientes**, opciones disponibles:
   - âœ… **Aprobar**: Activa la cuenta
   - âŒ **Rechazar**: Elimina el registro

### Al Aprobar:

1. Sistema actualiza:
   - `aprobado = 1`
   - `aprobado_por` = ID del admin
   - `aprobado_at` = fecha actual
   - `estado = 1` (activa la cuenta)
2. Se envÃ­a email de aprobaciÃ³n con link de acceso
3. Shopper puede iniciar sesiÃ³n con sus credenciales

### Al Rechazar:

1. Sistema marca como eliminado: `deleted_at = fecha actual`
2. Se envÃ­a email de rechazo (opcional: con motivo)
3. Registro queda inactivo permanentemente

---

## Flujo Principal del Sistema

### 1. Crear Restaurante

**Ruta**: `sistema.check360.cl/restaurantes/nuevo`

**Datos requeridos**:
- Nombre del restaurante (*)
- Tipo de cocina (*)
- RUT
- DirecciÃ³n
- RegiÃ³n / Ciudad
- TelÃ©fono
- Email
- Horarios
- Capacidad
- Opciones (delivery, reservas, etc.)

**Usuario del Restaurante**:
- Al crear restaurante, se crea automÃ¡ticamente un usuario en `restaurante_users`
- Se genera contraseÃ±a temporal
- Se envÃ­a email con credenciales
- Puede acceder a `restaurante.check360.cl`

### 2. Crear/Aprobar Mistery Shopper

**OpciÃ³n A - Desde Sistema**:
- Admin crea shopper â†’ Auto-aprobado
- Se envÃ­a email con credenciales
- Puede iniciar sesiÃ³n inmediatamente

**OpciÃ³n B - Registro PÃºblico**:
- Shopper se registra â†’ Pendiente aprobaciÃ³n
- Admin aprueba desde lista
- Se envÃ­a email de aprobaciÃ³n
- Puede iniciar sesiÃ³n

### 3. Crear Visita

**Ruta**: `sistema.check360.cl/visitas/nuevo`

**Datos requeridos**:
- Restaurante (*)
- Mistery Shopper (solo shoppers aprobados y activos) (*)
- Fecha programada (*)
- Hora estimada
- Encuesta de entrada (*)
- Encuesta de salida (*)
- Observaciones

**Estados de Visita**:
1. **Pendiente**: ReciÃ©n creada, esperando que el shopper la realice
2. **En Proceso**: Shopper respondiÃ³ encuesta de entrada
3. **Completada**: Shopper respondiÃ³ encuesta de salida
4. **No Realizada**: Visita no se realizÃ³ en la fecha
5. **Rechazada**: Admin rechazÃ³ la visita

### 4. Realizar Visita (Shopper)

**Flujo del Mistery Shopper**:

1. **Inicia sesiÃ³n** en `shopper.check360.cl`
2. **Ve sus visitas asignadas** en dashboard o `/visitas`
3. **Antes de la visita**:
   - Click en "Responder Entrada"
   - Completa encuesta de entrada
   - Estado cambia a "En Proceso"
4. **Durante/DespuÃ©s de la visita**:
   - Click en "Responder Salida"
   - Completa encuesta de salida
   - Estado cambia a "Completada"
5. **Visita finalizada** â†’ Admin puede revisar resultados

### 5. Revisar Resultados

**Desde Sistema**:
- Admin accede a `sistema.check360.cl/visitas/ver/{id}`
- Ve respuestas de encuesta de entrada y salida
- Puede descargar reportes
- Puede ver estadÃ­sticas

**Desde Restaurante**:
- Usuario restaurante accede a `restaurante.check360.cl/visitas`
- Ve resultados de visitas a su restaurante
- Dashboard con mÃ©tricas de calidad

---

## ConfiguraciÃ³n de Encuestas

### Tipos de Encuestas:

1. **Encuesta de Entrada**: Se responde antes/al inicio de la visita
2. **Encuesta de Salida**: Se responde despuÃ©s de la visita

### GestiÃ³n de Encuestas:

**Ruta**: `sistema.check360.cl/encuestas`

**Funcionalidades**:
- Ver lista de encuestas
- Ver preguntas de cada encuesta
- Crear nuevas preguntas
- Editar preguntas existentes
- Reordenar preguntas (drag & drop)
- Eliminar preguntas

**Tipos de Preguntas**:
- Texto libre
- OpciÃ³n mÃºltiple
- CalificaciÃ³n (escala)
- SÃ­/No
- Fecha
- Archivo (foto, documento)

---

## Resumen de Permisos por Subdominio

| Funcionalidad | Sistema | Restaurante | Shopper |
|--------------|---------|-------------|---------|
| GestiÃ³n de restaurantes | âœ… | âŒ | âŒ |
| GestiÃ³n de shoppers | âœ… | âŒ | âŒ |
| Aprobar shoppers | âœ… | âŒ | âŒ |
| Crear visitas | âœ… | âŒ | âŒ |
| Ver todas las visitas | âœ… | âŒ | âŒ |
| Ver visitas propias | âœ… | âœ… | âœ… |
| Responder encuestas | âŒ | âŒ | âœ… |
| Ver resultados | âœ… | âœ… | âœ… (propios) |
| GestiÃ³n de encuestas | âœ… | âŒ | âŒ |
| GestiÃ³n de usuarios | âœ… | âŒ | âŒ |
| Dashboard general | âœ… | âŒ | âŒ |
| Dashboard restaurante | âŒ | âœ… | âŒ |
| Dashboard shopper | âŒ | âŒ | âœ… |

---

## Estados y Validaciones

### Estados de Mistery Shopper:

- **estado**: 
  - `0` = Inactivo (no puede iniciar sesiÃ³n)
  - `1` = Activo (puede iniciar sesiÃ³n si estÃ¡ aprobado)

- **aprobado**:
  - `0` = Pendiente de aprobaciÃ³n (no puede iniciar sesiÃ³n)
  - `1` = Aprobado (puede iniciar sesiÃ³n si estÃ¡ activo)

**Para iniciar sesiÃ³n**: `estado = 1 AND aprobado = 1`

### Estados de Visita:

1. **Pendiente** (estado_id = 1): ReciÃ©n creada
2. **En Proceso** (estado_id = 2): Encuesta de entrada respondida
3. **Completada** (estado_id = 3): Encuesta de salida respondida
4. **No Realizada** (estado_id = 4): No se realizÃ³
5. **Rechazada** (estado_id = 5): Rechazada por admin

---

## Migraciones Importantes

Para aplicar los cambios de aprobaciÃ³n de shoppers, ejecutar:

```bash
php artisan migrate:fresh --seed
```

**NOTA**: Esto recrearÃ¡ todas las tablas. Si tienes datos en producciÃ³n, crear una migraciÃ³n especÃ­fica para agregar los campos:
- `aprobado` (integer, default 0)
- `aprobado_por` (foreignId nullable)
- `aprobado_at` (dateTime nullable)

---

## Emails AutomÃ¡ticos

### Mistery Shoppers:

1. **Registro recibido**: Cuando se registra pÃºblicamente
2. **Cuenta aprobada**: Cuando admin aprueba la cuenta
3. **Cuenta rechazada**: Cuando admin rechaza la cuenta
4. **Credenciales de acceso**: Cuando admin crea el shopper

### Restaurantes:

1. **Bienvenida**: Cuando se crea el restaurante
2. **Credenciales de acceso**: Con usuario y contraseÃ±a temporal

### Visitas:

1. **Visita asignada**: Al shopper cuando se crea una visita
2. **Visita completada**: Al restaurante cuando se completa

---

## PrÃ³ximos Pasos Recomendados

1. âœ… Crear restaurante
2. âœ… Crear/Aprobar Mistery Shopper
3. âœ… Configurar encuestas (entrada y salida)
4. âœ… Crear visita asignando restaurante y shopper
5. âœ… Shopper responde encuesta de entrada
6. âœ… Shopper realiza la visita
7. âœ… Shopper responde encuesta de salida
8. âœ… Revisar resultados y generar reportes

---

**DocumentaciÃ³n actualizada**: 2025-01-06
