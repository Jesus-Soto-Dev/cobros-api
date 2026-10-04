# Cobros API

API REST para la gestión de deudas y recobro, construida con **Laravel 13**. Permite registrar deudores y deudas, anotar las gestiones realizadas (llamadas, emails, promesas de pago) y controlar los pagos parciales.

> Proyecto personal de aprendizaje, inspirado en el flujo de trabajo de una empresa de gestión de cobros.

> **Estado del proyecto**: en desarrollo activo. Actualmente están implementados
> la autenticación con Sanctum y el CRUD de deudores y deudas (con filtros,
> paginación y control de roles). Los endpoints de gestiones y pagos, los tests
> y el CI se están implementando. Última actualización: 2026-10-04.

## Stack

- PHP 8.5 / Laravel 13
- MySQL 8 (o PostgreSQL)
- Laravel Sanctum (autenticación por token)
- Pest (tests)
- Docker con Laravel Sail
- GitHub Actions (CI)

## Funcionalidades

- Autenticación con tokens y dos roles: `admin` y `gestor`.
- CRUD de deudores y deudas.
- Estados de deuda: `pendiente`, `en_gestion`, `pagada`, `vencida`, `incobrable`.
- Historial de gestiones por deuda (llamada, email, promesa de pago).
- Registro de pagos parciales; la deuda pasa a `pagada` al cubrir el total.
- Filtros por estado, deudor y fecha de vencimiento.
- Comando Artisan `debts:mark-overdue` para marcar deudas vencidas (programado a diario).

## Puesta en marcha

Requisitos: Docker, Git y Composer.

```bash
git clone https://github.com/Jesus-Soto-Dev/cobros-api.git
cd cobros-api
cp .env.example .env
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

La API queda disponible en `http://localhost/api`.

Usuarios de prueba creados por el seeder:

| Rol    | Email               | Contraseña |
|--------|---------------------|------------|
| admin  | admin@example.com   | password   |
| gestor | gestor@example.com  | password   |

## Endpoints principales

Todos salvo el login requieren la cabecera `Authorization: Bearer <token>`.

| Método | Ruta                          | Descripción                          | Rol          | Estado |
|--------|-------------------------------|--------------------------------------|--------------|--------|
| POST   | `/api/login`                  | Obtener token                        | público      | ✅     |
| POST   | `/api/logout`                 | Revocar token                        | autenticado  | ✅     |
| GET    | `/api/debtors`                | Listar deudores                      | autenticado  | ✅     |
| POST   | `/api/debtors`                | Crear deudor                         | admin        | ✅     |
| GET    | `/api/debts`                  | Listar deudas (`?status=&debtor_id=`)| autenticado  | ✅     |
| POST   | `/api/debts`                  | Crear deuda                          | admin        | ✅     |
| GET    | `/api/debts/{id}`             | Detalle con gestiones y pagos        | autenticado  | ✅     |
| POST   | `/api/debts/{id}/actions`     | Registrar gestión                    | autenticado  | ⏳     |
| POST   | `/api/debts/{id}/payments`    | Registrar pago                       | autenticado  | ⏳     |
| GET    | `/api/me`                     | Usuario autenticado                  | autenticado  | ✅     |
| PUT    | `/api/debtors/{id}`           | Actualizar deudor                    | admin        | ✅     |
| DELETE | `/api/debtors/{id}`           | Eliminar deudor                      | admin        | ✅     |
| PUT    | `/api/debts/{id}`             | Actualizar deuda                     | admin        | ✅     |
| DELETE | `/api/debts/{id}`             | Eliminar deuda                       | admin        | ✅     |


### Ejemplo

```bash
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"gestor@example.com","password":"password"}'
```



## Tests

> **Pendiente**: la suite de tests (Pest) y el workflow de GitHub Actions se
> añadirán en la siguiente iteración.

```bash
./vendor/bin/sail artisan test
```

## Modelo de datos

```
users ──< debt_actions >── debts >── debtors
                            │
                            └──< payments
```

- `debtors`: nombre, documento, email, teléfono.
- `debts`: deudor, importe, vencimiento, estado.
- `debt_actions`: deuda, usuario, tipo, notas, fecha.
- `payments`: deuda, importe, fecha.

## Uso de asistentes de IA (IA agéntica)

Este proyecto se ha desarrollado apoyándome en asistentes de IA agéntica
integrados en el editor, siguiendo el flujo de trabajo que se describe a
continuación:

- **Asistente principal**: Antigravity (IDE con agente integrado sobre Gemini),
  conectado al proyecto en WSL2.
- **Metodología**: yo tomo las decisiones de diseño (modelo de datos, relaciones,
  contratos de la API, validaciones) y el asistente ejecuta tareas repetitivas o
  acotadas (generación de tests base, factories, seeders, refactors mecánicos,
  análisis de errores).
- **Revisión**: reviso siempre el código generado, entiendo qué hace cada línea
  y lo ajusto cuando no encaja con los requisitos o con las convenciones del
  proyecto.

### Tareas delegadas (se irá actualizando a medida que avance el proyecto)

| Tarea                          | Asistente (Antigravity)               | Yo                                          |
|--------------------------------|----------------------------------------|---------------------------------------------|
| Setup del entorno (WSL, Docker, Sail) | Sugerencias en errores de configuración | Debugging manual y decisiones           |
| Enums y modelos                | Apoyo en sintaxis de PHP 8             | Diseño del modelo de datos y relaciones     |
| CRUD de la API                 | Explicaciones puntuales de sintaxis    | Estructura de controladores, validaciones, filtros |
| Debugging                      | Análisis de errores del framework      | Verificación y ajuste de la solución        |
| Debugging de Eloquent          | Detección del problema (`status` null tras create) | Decisión de usar `refresh()` tras crear |

> Esta tabla se actualizará al finalizar el proyecto con ejemplos concretos de
> cada tipo de tarea.

### Notas sobre el entorno

Durante el montaje del entorno se resolvieron problemas típicos de Windows:
integración de Docker Desktop con WSL2, conflictos de puertos con MySQL del
sistema anfitrión y adaptación del flujo de Laravel Sail para que funcione
dentro del sistema de archivos de WSL.

## Posibles mejoras

- Panel en Vue o Blade para gestores.
- Exportación de informes a CSV.
- Notificaciones por email al registrar una promesa de pago.
- Paginación y búsqueda avanzada.

## Licencia

MIT