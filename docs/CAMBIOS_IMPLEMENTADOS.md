# Cambios Implementados - Check 360

## Fecha: 2025-01-06

---

## ðŸ”§ Cambios Realizados

### 1. âœ… CorrecciÃ³n del icono "show" en campos de contraseÃ±a

**Problema**: En los formularios de login, el campo de contraseÃ±a mostraba el texto "show" en lugar del icono para mostrar/ocultar contraseÃ±a.

**SoluciÃ³n**: 
- Se corrigiÃ³ el HTML de `login.blade.php` para usar la estructura correcta con `position-relative`
- Se agregÃ³ JavaScript personalizado para manejar el toggle del campo password

**Archivos modificados**:
- `resources/views/auth/login.blade.php`

---

### 2. âœ… BotÃ³n "RegÃ­strate" en login de shopper

**Cambio**: Se agregÃ³ un botÃ³n de registro en el login que solo aparece cuando accedes desde el subdominio shopper (`shopper.check360.cl`).

**Funcionalidad**:
- Detecta automÃ¡ticamente el subdominio usando `SubdominioHelper::esTipo('shopper')`
- Muestra mensaje "Â¿AÃºn no tienes cuenta?"
- Link al formulario de registro pÃºblico

**Archivos modificados**:
- `resources/views/auth/login.blade.php`

---

### 3. âœ… Formulario de registro pÃºblico para Mistery Shoppers

**Nueva funcionalidad**: Los Mistery Shoppers pueden registrarse pÃºblicamente desde `shopper.check360.cl/registro`

**Campos del formulario**:
- Nombre completo (*)
- Correo electrÃ³nico (*)
- ContraseÃ±a (*) con opciÃ³n show/hide
- TelÃ©fono
- Observaciones / Experiencia previa

**Comportamiento**:
- La cuenta queda en estado "Pendiente de aprobaciÃ³n"
- `estado = 0` (inactivo)
- `aprobado = 0` (pendiente)
- Se envÃ­a email de confirmaciÃ³n de registro recibido
- NO puede iniciar sesiÃ³n hasta ser aprobado

**Archivos creados**:
- `resources/views/auth/shopper_register.blade.php`

---

### 4. âœ… Sistema de aprobaciÃ³n de Mistery Shoppers

**Nueva funcionalidad**: Sistema de dos estados para Mistery Shoppers:

#### Campos agregados a la tabla `mistery_shoppers`:
- `aprobado` (integer, default 0): 0=pendiente, 1=aprobado
- `aprobado_por` (foreignId nullable): ID del admin que aprobÃ³
- `aprobado_at` (dateTime nullable): Fecha de aprobaciÃ³n

#### LÃ³gica de aprobaciÃ³n:

**OpciÃ³n A - Registro pÃºblico**:
1. Shopper se registra â†’ `aprobado = 0`, `estado = 0`
2. Admin aprueba desde lista
3. Sistema actualiza â†’ `aprobado = 1`, `estado = 1`, registra quiÃ©n y cuÃ¡ndo
4. Se envÃ­a email de aprobaciÃ³n
5. Shopper puede iniciar sesiÃ³n

**OpciÃ³n B - CreaciÃ³n desde sistema**:
1. Admin crea shopper desde panel
2. Sistema auto-aprueba â†’ `aprobado = 1`, `estado = 1`
3. Se genera contraseÃ±a temporal
4. Se envÃ­a email con credenciales
5. Shopper puede iniciar sesiÃ³n inmediatamente

**Archivos modificados**:
- `database/migrations/2025_01_20_000001_create_mistery_shoppers_table.php`
- `app/Models/MisteryShopper.php`
- `app/Http/Controllers/MisteryShopperController.php`
- `app/Http/Controllers/UserController.php`

---

### 5. âœ… Interfaz de aprobaciÃ³n en lista de shoppers

**Cambios en la vista**:
- Nueva columna "AprobaciÃ³n" con badges:
  - ðŸŸ¢ Badge verde "Aprobado"
  - ðŸŸ¡ Badge amarillo "Pendiente"
- Opciones de menÃº contextual para shoppers pendientes:
  - âœ… Aprobar
  - âŒ Rechazar (con opciÃ³n de agregar motivo)
- El switch de activar/desactivar se deshabilita si no estÃ¡ aprobado

**Funcionalidades**:
- Aprobar: Activa cuenta y envÃ­a email
- Rechazar: Elimina registro y envÃ­a email (opcional con motivo)
- Modal de confirmaciÃ³n con SweetAlert

**Archivos modificados**:
- `resources/views/shoppers/lista.blade.php`

---

### 6. âœ… ValidaciÃ³n de inicio de sesiÃ³n

**Nueva validaciÃ³n**: Un Mistery Shopper solo puede iniciar sesiÃ³n si:
- `estado = 1` (activo) **Y**
- `aprobado = 1` (aprobado)

**Mensajes de error**:
- Estado 503: "Tu cuenta estÃ¡ pendiente de aprobaciÃ³n. RecibirÃ¡s un correo cuando sea activada."
- Estado 501: "Tu cuenta estÃ¡ inactiva. Contacta al administrador."
- Estado 500: "ContraseÃ±a incorrecta"
- Estado 404: "Usuario no encontrado"

**Archivos modificados**:
- `app/Http/Controllers/UserController.php`
- `resources/views/auth/login.blade.php`

---

### 7. âœ… Emails automÃ¡ticos

**Nuevas plantillas de email**:

1. **shopper_registro.blade.php**: ConfirmaciÃ³n de registro recibido
   - Se envÃ­a cuando el shopper se registra pÃºblicamente
   - Informa que la cuenta estÃ¡ pendiente de aprobaciÃ³n

2. **shopper_aprobado.blade.php**: Cuenta aprobada
   - Se envÃ­a cuando el admin aprueba la cuenta
   - Incluye link de acceso a la plataforma
   - Lista de prÃ³ximos pasos

3. **shopper_rechazado.blade.php**: Registro no aprobado
   - Se envÃ­a cuando el admin rechaza el registro
   - Incluye motivo del rechazo (si se proporcionÃ³)

**Archivos creados**:
- `resources/views/mails/shopper_registro.blade.php`
- `resources/views/mails/shopper_aprobado.blade.php`
- `resources/views/mails/shopper_rechazado.blade.php`

---

### 8. âœ… Rutas agregadas

**Nuevas rutas pÃºblicas**:
```php
Route::get('/registro', [MisteryShopperController::class, 'registroPublico'])->name('shopper.registro');
Route::post('/registro', [MisteryShopperController::class, 'registroPublicoPost'])->name('shopper.registro.post');
```

**Nuevas rutas protegidas (dentro de middleware)**:
```php
Route::post('/shoppers/aprobar', [MisteryShopperController::class, 'aprobar'])->name('shoppers.aprobar');
Route::post('/shoppers/rechazar', [MisteryShopperController::class, 'rechazar'])->name('shoppers.rechazar');
```

**Archivos modificados**:
- `routes/web.php`

---

### 9. âœ… MÃ©todos agregados al controlador

**MisteryShopperController**:
- `registroPublico()`: Muestra formulario de registro
- `registroPublicoPost()`: Procesa registro pÃºblico
- `aprobar()`: Aprueba un Mistery Shopper
- `rechazar()`: Rechaza un Mistery Shopper

**MÃ©todos modificados**:
- `store()`: Diferencia entre creaciÃ³n desde sistema (auto-aprobado) y registro pÃºblico (pendiente)

**Archivos modificados**:
- `app/Http/Controllers/MisteryShopperController.php`

---

### 10. âœ… Helper de subdominios actualizado

**ConfiguraciÃ³n actual**: El helper `SubdominioHelper` ahora estÃ¡ configurado para que localhost devuelva 'shopper' por defecto (lÃ­nea 18).

```php
if ($host === 'localhost' || $host === '127.0.0.1' || strpos($host, 'localhost') !== false) {
    return 'shopper';
}
```

**Nota**: Puedes cambiar esto a 'sistema' o 'restaurante' segÃºn necesites para desarrollo.

**Archivo**:
- `app/Helpers/SubdominioHelper.php`

---

## ðŸ“‹ Instrucciones para Aplicar los Cambios

### 1. Ejecutar las migraciones

**IMPORTANTE**: Esto recrearÃ¡ todas las tablas. Solo hazlo en desarrollo.

```bash
php artisan migrate:fresh --seed
```

**Para producciÃ³n**, crear una migraciÃ³n especÃ­fica:

```bash
php artisan make:migration add_aprobacion_to_mistery_shoppers_table
```

Y agregar solo los campos nuevos:

```php
public function up()
{
    Schema::table('mistery_shoppers', function (Blueprint $table) {
        $table->integer('aprobado')->default(0)->after('estado');
        $table->foreignId('aprobado_por')->nullable()->after('aprobado')->constrained('users')->onDelete('set null');
        $table->dateTime('aprobado_at')->nullable()->after('aprobado_por');
        $table->index('aprobado');
    });
}

public function down()
{
    Schema::table('mistery_shoppers', function (Blueprint $table) {
        $table->dropForeign(['aprobado_por']);
        $table->dropColumn(['aprobado', 'aprobado_por', 'aprobado_at']);
    });
}
```

Luego ejecutar:

```bash
php artisan migrate
```

### 2. Limpiar cachÃ©

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 3. Verificar configuraciÃ³n de email

AsegÃºrate de tener configurado el servidor de email en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseÃ±a_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
MAIL_FROM_NAME="Check 360"
```

---

## ðŸ§ª Pruebas Recomendadas

### Flujo de registro pÃºblico:

1. Acceder a `shopper.check360.cl/login` (o localhost con SubdominioHelper configurado en 'shopper')
2. Verificar que aparece el botÃ³n "RegÃ­strate como Mistery Shopper"
3. Click en registrarse
4. Completar formulario
5. Verificar email de confirmaciÃ³n
6. Intentar iniciar sesiÃ³n â†’ Debe mostrar "Pendiente de aprobaciÃ³n"

### Flujo de aprobaciÃ³n:

1. Acceder a `sistema.check360.cl` con usuario admin
2. Ir a "Mistery Shoppers"
3. Verificar que aparece el registro con badge "Pendiente"
4. Click en menÃº â†’ "Aprobar"
5. Confirmar aprobaciÃ³n
6. Verificar que cambiÃ³ a badge "Aprobado"
7. Verificar email de aprobaciÃ³n enviado al shopper
8. Como shopper, intentar iniciar sesiÃ³n â†’ Debe permitir acceso

### Flujo de creaciÃ³n desde sistema:

1. Acceder a `sistema.check360.cl` con usuario admin
2. Ir a "Mistery Shoppers" â†’ "Nuevo Mistery Shopper"
3. Completar formulario
4. Guardar
5. Verificar que aparece con badge "Aprobado" inmediatamente
6. Verificar email con credenciales enviado
7. Como shopper, iniciar sesiÃ³n con credenciales recibidas

---

## ðŸ“– DocumentaciÃ³n Adicional

Se ha creado el documento `FLUJO_SISTEMA.md` que incluye:
- DescripciÃ³n de los 3 subdominios
- Flujo completo de registro y aprobaciÃ³n
- Flujo principal del sistema (restaurante â†’ shopper â†’ visita)
- Tabla de permisos por subdominio
- Estados y validaciones
- Emails automÃ¡ticos
- PrÃ³ximos pasos recomendados

**UbicaciÃ³n**: `docs/FLUJO_SISTEMA.md`

---

## ðŸ› Posibles Problemas y Soluciones

### Problema 1: "Class SubdominioHelper not found"
**SoluciÃ³n**: 
```bash
composer dump-autoload
```

### Problema 2: Error al enviar emails
**SoluciÃ³n**: Verificar configuraciÃ³n MAIL_ en .env y que el servidor SMTP estÃ© accesible

### Problema 3: MigraciÃ³n falla por foreign key
**SoluciÃ³n**: Asegurarse de que la tabla `users` existe antes de `mistery_shoppers`

### Problema 4: No aparece el botÃ³n de registro en login
**SoluciÃ³n**: Verificar que `SubdominioHelper::obtenerTipo()` estÃ© retornando 'shopper'

---

## ðŸ“ž Contacto y Soporte

Para dudas o problemas con la implementaciÃ³n, contactar al equipo de desarrollo.

---

**DocumentaciÃ³n creada**: 2025-01-06
