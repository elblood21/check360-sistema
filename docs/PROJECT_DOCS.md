# DocumentaciÃ³n del Proyecto: Check 360

## DescripciÃ³n General
**Check 360** es una plataforma de gestiÃ³n de auditorÃ­as de calidad para la industria gastronÃ³mica mediante la metodologÃ­a de "Mistery Shopper" (Cliente IncÃ³gnito). El sistema permite a las empresas evaluar la experiencia del cliente, la calidad del servicio y el cumplimiento de estÃ¡ndares operativos en tiempo real.

## Arquitectura TÃ©cnica
- **Framework:** Laravel 11.x
- **Base de Datos:** MySQL / MariaDB
- **Frontend:** Blade Templates, Bootstrap 5, ApexCharts para visualizaciÃ³n de datos.
- **Multitenancy:** Sistema basado en subdominios para diferenciar los portales:
  - `sistema.check360.cl`: AdministraciÃ³n global.
  - `restaurante.check360.cl`: Portal para gerentes y dueÃ±os de locales.
  - `shopper.check360.cl`: Portal para auditorÃ­a y registro de Mistery Shoppers.

## Roles de Usuario
### 1. Administrador del Sistema (Sistema)
- GestiÃ³n de catÃ¡logos (Tipos de cocina, Restaurantes, Shoppers).
- DiseÃ±o de encuestas (Preguntas de Expectativas y Experiencia).
- AsignaciÃ³n de visitas a Shoppers.
- AuditorÃ­a global de resultados y reportes.

### 2. Administrador de Restaurante (Restaurante)
- VisualizaciÃ³n de estadÃ­sticas de desempeÃ±o de sus locales.
- Consulta de encuestas respondidas de sus visitas.
- GestiÃ³n de usuarios internos.

### 3. Mistery Shopper (Shopper)
- Auto-registro en la plataforma.
- VisualizaciÃ³n de visitas asignadas.
- CompletaciÃ³n de encuestas en dos etapas:
  1. **Expectativas (Entrada):** Antes de consumir.
  2. **Experiencia (Salida):** DespuÃ©s de la visita.
- Dashboard personal con historial de actividades.

## Requisitos de InstalaciÃ³n (Local)
1. PHP 8.2+
2. Composer
3. MySQL
