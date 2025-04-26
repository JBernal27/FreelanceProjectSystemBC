# Documentación de Endpoints API

## Usuarios (Users)

Endpoints para gestión de usuarios.

| Método | Ruta          | Descripción                        | Parámetros / Body                                |
|--------|---------------|----------------------------------|-------------------------------------------------|
| GET    | /users        | Obtener lista de todos los usuarios | Sin parámetros                                  |
| GET    | /users/{id}   | Obtener un usuario por su ID       | Ruta: `id` (número)                             |
| POST   | /users        | No permitido en este controlador. Registrar usuario se hace vía `/auth/register`. | -                                               |
| PUT    | /users/{id}   | Actualizar usuario existente       | Body JSON con: `name`, `email`, `password`     |
| DELETE | /users/{id}   | Eliminar usuario por ID             | Ruta: `id` (número)                             |

---

## Proyectos (Projects)

Endpoints para gestión de proyectos.

| Método | Ruta                        | Descripción                                   | Parámetros / Body                                                                                  |
|--------|-----------------------------|-----------------------------------------------|--------------------------------------------------------------------------------------------------|
| GET    | /projects                   | Obtener lista de todos los proyectos          | Sin parámetros                                                                                   |
| GET    | /projects/{id}              | Obtener proyecto por ID                       | Ruta: `id` (número)                                                                              |
| POST   | /projects                   | Crear un nuevo proyecto                       | Body JSON con: `title`, `description`, `start_date`, `delivery_date`, `status`, `user_id`       |
| PUT    | /projects/{id}              | Actualizar proyecto existente                 | Body JSON con: `title`, `description`, `start_date`, `delivery_date`, `status`, `user_id`       |
| DELETE | /projects/{id}              | Eliminar proyecto por ID                      | Ruta: `id` (número)                                                                              |
| GET    | /projects/user/{user_id}    | Obtener proyectos por ID de usuario           | Ruta: `user_id` (número)                                                                        |
| POST   | /projects/{id}/upload       | Sube un archivo relacionado con un proyecto   | Ruta: `id` (número)<br>Body: archivo en campo `file` (form-data)                                |
| GET    | /projects/{id}/files        | Obtiene todos los archivos de un proyecto     | Ruta: `id` (número)                                                                             |

### Respuestas de archivos

- **POST /projects/{id}/upload:**
    - 200 OK: Archivo subido exitosamente.
    - 400 Bad Request: No se proporcionó un archivo.
    - 500 Internal Server Error: Error al subir el archivo.
- **GET /projects/{id}/files:**
    - 200 OK: Lista de archivos.
    - 404 Not Found: No se encontraron archivos para este proyecto.

---

## Autenticación (Auth)

Endpoints para login y registro de usuarios.

| Método | Ruta           | Descripción                      | Body JSON                                       |
|--------|----------------|--------------------------------|------------------------------------------------|
| POST   | /auth/login    | Inicio de sesión (login)        | `email`, `password`                            |
| POST   | /auth/register | Registro de nuevo usuario       | `name`, `email`, `password`                    |

> El login devuelve un token JWT válido por 1 día si las credenciales son correctas.

---

Esta documentación resume los endpoints principales y su uso basado en la implementación PHP de los controladores y modelos.
