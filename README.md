# Admin Panel - Gestión de Grupos y Usuarios

Este proyecto es una aplicación FullStack desarrollada con **Laravel (PHP)** para el backend y **Angular 17** para el frontend. Permite la creación y gestión de grupos de usuarios, así como la asignación de usuarios a grupos de manera reactiva y segura.

---

## Tecnologías utilizadas

### Backend
- **Laravel 10** (PHP 8+)
- **MySQL** como base de datos
- Autenticación y autorización mediante **Laravel Sanctum**
- Encriptación de datos sensibles usando funciones estándar de Laravel (`bcrypt`, `Hash::make()`)
- Arquitectura de **API REST** para consumo desde Angular
- Modelos de Eloquent para `Usuarios`, `Grupos` y relaciones `UsuariosGrupos`
- Validación de datos mediante Requests y reglas de Laravel
- Middleware para proteger rutas según permisos y autenticación

### Frontend
- **Angular 17** (Standalone Components)
- Uso de **Signals** para manejo reactivo de estados y binding de datos
- Componentes principales:
  - `AdminPanelComponent`: Gestión de grupos y asignación de usuarios
- Estilos con **SCSS** modernos y responsivos
- Renderizado dinámico de listas y selects mediante funciones y señales reactivas (no `*ngFor`/`*ngIf`)
- Manejo de formularios y eventos mediante bindings reactivos

### Seguridad
- Autenticación mediante **Laravel Sanctum**, que permite tokens de sesión seguros
- Encriptación de contraseñas y datos sensibles en la base de datos
- Protección de rutas y control de acceso en backend

---

## Funcionalidades

### Backend
1. CRUD de **Usuarios** y **Grupos**
2. Asignación de usuarios a grupos
3. Endpoints REST seguros con autenticación Sanctum
4. Validación de datos de entrada
5. Manejo de errores y respuestas claras

### Frontend
1. Creación de grupos directamente desde el panel
2. Visualización de usuarios dentro de cada grupo
3. Asignación de usuarios a grupos mediante selects reactivos
4. UI moderna y responsiva
5. Manejo de estados y eventos mediante Angular 17 Signals

---

## Instalación y ejecución

### Backend (Laravel)
1. Clonar el proyecto
2. Instalar dependencias:  
   ```bash
   composer install
