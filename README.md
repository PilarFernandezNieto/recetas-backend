# Libro de Recetas — Backend

API REST desarrollada con **Laravel 11 + Sanctum** que gestiona recetas, ingredientes, categorías, dificultades y usuarios. Expone rutas públicas de consulta y un panel de administración protegido.

---

## Requisitos previos

- PHP >= 8.2
- Composer
- SQLite (por defecto) o MySQL/PostgreSQL

---

## Instalación y puesta en marcha

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link     # enlaza storage/app/public → public/storage (imágenes)
php artisan serve --host=localhost --port=8000
```

La API queda disponible en **http://localhost:8000**.

### Variables de entorno clave (`.env`)

| Variable                   | Descripción                                      | Ejemplo                 |
| -------------------------- | ------------------------------------------------ | ----------------------- |
| `APP_URL`                  | URL del propio backend                           | `http://localhost:8000` |
| `DB_CONNECTION`            | Motor de base de datos                           | `sqlite` / `mysql`      |
| `FRONTEND_URL`             | URL del frontend Vue (para CORS)                 | `http://localhost:3000` |
| `SANCTUM_STATEFUL_DOMAINS` | Dominios con acceso a sesión (sin protocolo)     | `localhost:3000`        |
| `SESSION_DOMAIN`           | Dominio de la cookie de sesión                   | `localhost`             |
| `MAIL_*`                   | Configuración de correo (recuperación contraseña)| —                       |
| `FILESYSTEM_DISK`          | Disco de almacenamiento de imágenes              | `public`                |

> **En producción** configura `SESSION_SECURE_COOKIE=true`, `SESSION_SAME_SITE=none` (si frontend y backend están en dominios distintos) y un driver de caché real (`CACHE_DRIVER=redis` o `database`).

---

## Estructura del proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── MainController.php         # Rutas públicas: listado y detalle de recetas
│   │   ├── RecetaController.php       # CRUD recetas (admin)
│   │   ├── IngredienteController.php  # CRUD ingredientes + listado completo sin paginar
│   │   ├── CategoriaController.php    # CRUD categorías
│   │   ├── DificultadController.php   # CRUD dificultades (con caché permanente)
│   │   ├── UsuarioController.php      # Gestión de usuarios (admin)
│   │   └── Auth/                      # Breeze: login, registro, reset, verificación
│   │
│   ├── Middleware/
│   │   ├── UserIsAdmin.php            # Verifica is_admin=true en el usuario autenticado
│   │   └── EnsureEmailIsVerified.php
│   │
│   ├── Requests/
│   │   ├── RecetaRequest.php          # Validación de creación/edición de receta
│   │   ├── IngredienteRequest.php
│   │   ├── CategoriaRequest.php
│   │   └── UsuarioRequest.php
│   │
│   └── Resources/
│       ├── RecetaCollection.php       # Colección paginada de recetas
│       ├── CategoriaResource.php / CategoriaCollection.php
│       ├── IngredienteResource.php / IngredienteCollection.php
│       ├── DificultadResource.php / DificultadCollection.php
│       └── UsuarioResource.php / UsuarioCollection.php
│
├── Models/
│   ├── Receta.php              # Relaciones: ingredientes (pivot), categoria, dificultad
│   ├── Ingrediente.php
│   ├── RecetaIngrediente.php   # Pivot con campos extra: cantidad, unidad
│   ├── Categoria.php
│   ├── Dificultad.php
│   └── User.php                # Campo extra: is_admin (bool)
│
└── Traits/
    └── ImageHandler.php        # Sube, reemplaza y elimina imágenes del disco

routes/
├── api.php                     # Rutas de la API
└── auth.php                    # Rutas de autenticación Breeze

database/
└── migrations/                 # Historial completo del esquema
```

---

## Esquema de base de datos

```
users
  id, name, email, password, is_admin (bool), email_verified_at, timestamps

ingredientes
  id, nombre (unique), descripcion (nullable)

dificultades
  id, nombre

categorias
  id, nombre

recetas
  id, nombre, intro (nullable), instrucciones (text — HTML de TinyMCE),
  imagen (nullable), comensales, tiempo, origen,
  dificultad_id (FK → dificultades),
  categoria_id (FK → categorias, nullable)

receta_ingredientes   ← tabla pivot con datos extra
  id, receta_id (FK → recetas, cascade delete),
  ingrediente_id (FK → ingredientes, set null on delete),
  cantidad, unidad
```

`ingrediente_id` usa `ON DELETE SET NULL`: borrar un ingrediente no elimina las recetas donde aparecía, solo desvincula la relación.

---

## API Reference

### Rutas públicas

| Método | Ruta                | Descripción                                                    |
| ------ | ------------------- | -------------------------------------------------------------- |
| GET    | `/api/recetas`      | Listado paginado. Acepta `?buscar=` para filtrar por nombre.   |
| GET    | `/api/recetas/{id}` | Detalle con ingredientes, categoría y dificultad.              |

### Rutas de autenticación

Gestionadas por Laravel Breeze. Throttle `6,1` (6 intentos/minuto) en todas.

| Método | Ruta                               | Middleware   | Acción                          |
| ------ | ---------------------------------- | ------------ | ------------------------------- |
| POST   | `/register`                        | guest        | Crear cuenta                    |
| POST   | `/login`                           | guest        | Iniciar sesión                  |
| POST   | `/logout`                          | auth         | Cerrar sesión                   |
| POST   | `/forgot-password`                 | guest        | Enviar email de recuperación    |
| POST   | `/reset-password`                  | guest        | Establecer nueva contraseña     |
| GET    | `/verify-email/{id}/{hash}`        | auth, signed | Verificar email                 |
| POST   | `/email/verification-notification` | auth         | Reenviar email de verificación  |

### Rutas de administración (`/api/admin/...`)

Protegidas por `auth:sanctum + verified + is_admin`.

| Método | Ruta                            | Descripción                                                  |
| ------ | --------------------------------| ------------------------------------------------------------ |
| GET    | `/admin/recetas`                | Listado paginado + `?buscar=`                                |
| POST   | `/admin/recetas`                | Crear receta (multipart/form-data con imagen opcional)       |
| GET    | `/admin/recetas/{id}`           | Detalle                                                      |
| POST   | `/admin/recetas/{id}`           | Actualizar (POST con `_method=PUT` — necesario por multipart)|
| DELETE | `/admin/recetas/{id}`           | Eliminar receta e imagen del disco                           |
| GET    | `/admin/ingredientes`           | Listado paginado + `?buscar=`                                |
| GET    | `/admin/ingredientes-todos`     | Lista completa sin paginar (para `<select>` del formulario)  |
| POST   | `/admin/ingredientes`           | Crear                                                        |
| PUT    | `/admin/ingredientes/{id}`      | Actualizar                                                   |
| DELETE | `/admin/ingredientes/{id}`      | Eliminar                                                     |
| CRUD   | `/admin/categorias`             | Igual que ingredientes                                       |
| CRUD   | `/admin/dificultades`           | Igual que ingredientes                                       |
| CRUD   | `/admin/usuarios`               | Gestión de usuarios                                          |

---

## Autenticación — Sanctum SPA

Este backend usa autenticación basada en **cookies de sesión**, no en tokens Bearer. El flujo desde el frontend es:

1. `GET /sanctum/csrf-cookie` — obtiene la cookie CSRF antes del primer login.
2. `POST /login` — establece la cookie de sesión `HttpOnly`.
3. Todas las peticiones incluyen `withCredentials: true` para enviar las cookies automáticamente.

Para que funcione en desarrollo, `SANCTUM_STATEFUL_DOMAINS` y `SESSION_DOMAIN` deben coincidir con el dominio del frontend (sin protocolo ni barra final).

---

## Imágenes — Trait `ImageHandler`

`app/Traits/ImageHandler.php` centraliza toda la gestión de imágenes:

- **Subida**: guarda el archivo en `storage/app/public/` y devuelve la ruta relativa.
- **Reemplazo**: elimina la imagen anterior del disco antes de guardar la nueva.
- **Eliminación**: borra el archivo cuando se elimina una receta.

Requiere haber ejecutado `php artisan storage:link` para que las imágenes sean accesibles en `public/storage`.

---

## Caché

`DificultadController` usa `Cache::rememberForever('dificultades', ...)` porque las dificultades son datos estáticos. Si modificas los registros de dificultades directamente en la BD, limpia la caché:

```bash
php artisan cache:clear
```

---

## Comandos Artisan útiles

```bash
php artisan migrate:fresh          # Reinicia la BD (borra todo y vuelve a migrar)
php artisan storage:link           # Crea el enlace simbólico para imágenes
php artisan cache:clear            # Limpia caché (necesario si modificas dificultades)
php artisan route:list --path=api  # Lista todas las rutas de la API
php artisan tinker                 # Consola interactiva de Laravel
```

---

## Tecnologías

| Herramienta     | Versión |
| --------------- | ------- |
| Laravel         | ^11.31  |
| Laravel Sanctum | ^4.0    |
| Laravel Breeze  | ^2.3    |
| PHP             | ^8.2    |
