# Proyecto de Administración de Usuarios y Grupos

Este proyecto es un sistema de administración de usuarios y grupos que permite crear grupos, asignar usuarios a grupos y visualizar la estructura de usuarios por grupo. Está desarrollado con **Laravel** en el backend y **Angular 17** en el frontend.

---

## Tecnologías

### Backend
- **Laravel 10**
- **PHP 8**
- **MySQL** como base de datos
- Autenticación y autorización mediante **Laravel Sanctum**
- Encriptación de datos sensibles usando mecanismos estándar de Laravel
- Arquitectura de **API REST** para consumo desde Angular
- Modelos principales: `Usuarios`, `Grupos`, `UsuariosGrupos`

### Frontend
- **Angular 17**
- TypeScript y HTML
- Componentes standalone
- Renderizado dinámico de listas y selects mediante **signals**
- Estilos simples en SCSS para una interfaz limpia y funcional

---

## Funcionalidades

### Backend
- CRUD de `Usuarios` y `Grupos`
- Asignación de usuarios a grupos
- Protección de rutas mediante **Sanctum** (token-based)
- Manejo de relaciones entre usuarios y grupos en la base de datos

### Frontend
- Creación de grupos
- Asignación de usuarios a grupos
- Visualización dinámica de usuarios dentro de cada grupo
- Formularios reactivos simples usando signals de Angular 17

---

## Instalación y ejecución

### Backend (Laravel)
1. Clonar el proyecto:  
   ```bash
   git clone <URL_DEL_PROYECTO_BACKEND>
   cd <CARPETA_DEL_PROYECTO_BACKEND>
Instalar dependencias:

bash
Copy code
composer install
Configurar la base de datos en el archivo .env:

env
Copy code
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base
DB_USERNAME=usuario
DB_PASSWORD=contraseña
Ejecutar migraciones y seeders:

bash
Copy code
php artisan migrate --seed
Iniciar el servidor backend:

bash
Copy code
php artisan serve
La API estará disponible en http://127.0.0.1:8000

Frontend (Angular 17)
Clonar el proyecto frontend:

bash
Copy code
git clone <URL_DEL_PROYECTO_FRONTEND>
cd <CARPETA_DEL_PROYECTO_FRONTEND>
Instalar dependencias:

bash
Copy code
npm install
Iniciar la aplicación Angular:

bash
Copy code
ng serve
Abrir en el navegador:

arduino
Copy code
http://localhost:4200
