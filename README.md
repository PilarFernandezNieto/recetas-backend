# 📖 Libro de Recetas — Backend

API REST desarrollada con **Laravel 11** que actúa como backend para la aplicación web _Libro de Recetas_. Gestiona recetas, ingredientes, categorías, dificultades y usuarios, con autenticación mediante **Laravel Sanctum**.

---

## 🗂️ Descripción general

Este proyecto expone una API JSON consumida por el frontend Vue 3. Incluye:

- **Recetas**: listado público y gestión completa desde el panel de administración.
- **Ingredientes**: catálogo de ingredientes reutilizables en cada receta.
- **Categorías y dificultades**: clasificación de las recetas.
- **Usuarios**: gestión de cuentas y roles.
- **Autenticación**: registro, login y logout mediante Sanctum (tokens de sesión con cookies).
- **Panel de administración**: rutas protegidas por los middlewares `auth:sanctum`, `verified` e `is_admin`.

### Rutas principales de la API

| Método | Ruta                      | Acceso  | Descripción                 |
| ------ | ------------------------- | ------- | --------------------------- |
| GET    | `/api/recetas`            | Público | Listado paginado de recetas |
| GET    | `/api/recetas/{id}`       | Público | Detalle de una receta       |
| CRUD   | `/api/admin/recetas`      | Admin   | Gestión de recetas          |
| CRUD   | `/api/admin/ingredientes` | Admin   | Gestión de ingredientes     |
| CRUD   | `/api/admin/categorias`   | Admin   | Gestión de categorías       |
| CRUD   | `/api/admin/dificultades` | Admin   | Gestión de dificultades     |
| CRUD   | `/api/admin/usuarios`     | Admin   | Gestión de usuarios         |

---

## ⚙️ Requisitos previos

- **PHP** >= 8.2
- **Composer**
- **Node.js** y **npm** (para Vite y assets)
- Una base de datos compatible (SQLite por defecto, o MySQL/PostgreSQL)

---

## 🚀 Instalación y puesta en marcha (desarrollo)

### 1. Instalar dependencias

```bash
composer install
npm install
```

### 2. Configurar el entorno

Copia el archivo de ejemplo y edítalo con tus valores:

```bash
cp .env.example .env
```

Variables clave en `.env`:

```dotenv
APP_NAME="Libro de Recetas"
APP_URL=http://localhost:8000

# Base de datos (SQLite por defecto, sin configuración adicional)
DB_CONNECTION=sqlite

# Si usas MySQL, descomenta y rellena:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=recetas
# DB_USERNAME=root
# DB_PASSWORD=

# URL del frontend (para CORS y Sanctum)
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
```

### 3. Generar la clave de la aplicación

```bash
php artisan key:generate
```

### 4. Ejecutar migraciones (y seeders, si los hay)

```bash
php artisan migrate
# php artisan db:seed   # opcional, si existen seeders
```

### 5. Iniciar el servidor de desarrollo

**Opción A — Básica (recomendada para uso habitual):**

```bash
php artisan serve --host=localhost --port=8000
```

> La API quedará disponible en **http://localhost:8000**

**Opción B — Avanzada (incluye cola de trabajos y logs en tiempo real):**

```bash
composer run dev
```

> Lanza en paralelo: servidor PHP, `queue:listen`, `pail` (logs) y Vite.  
> Útil si usas trabajos en cola o quieres monitorizar logs desde consola.

---

## 🔗 Frontend relacionado

El frontend (Vue 3 + Vite) se encuentra en la carpeta `recetas-frontend`. Consulta su propio `README.md` para iniciarlo.

---

## 🛠️ Tecnologías utilizadas

| Tecnología      | Versión                    |
| --------------- | -------------------------- |
| Laravel         | ^11.31                     |
| Laravel Sanctum | ^4.0                       |
| PHP             | ^8.2                       |
| Laravel Breeze  | ^2.3 (scaffolding de auth) |

---

## 📄 Licencia

Este proyecto está bajo la licencia [MIT](https://opensource.org/licenses/MIT).
