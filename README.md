# 💈 Sistema de Reservas para Barbería

Sistema web desarrollado para gestionar reservas de una barbería.

La aplicación permite que los clientes puedan registrarse, reservar turnos, administrar sus reservas y dejar reseñas. Además, cuenta con un panel administrativo para gestionar barberos, servicios, reservas y usuarios.

---

# 📌 Características

## Cliente

- Registro e inicio de sesión
- Reserva de turnos
- Selección de servicio
- Selección de barbero
- Selección de fecha y horario
- Visualización de reservas
- Cancelación de reservas
- Edición de perfil
- Sistema de reseñas

## Administrador

- Gestión de barberos
- Gestión de servicios
- Gestión de reservas
- Gestión de reseñas
- Asignación de servicios a barberos
- Cambio de estado de reservas

---

# 🛠 Tecnologías

## Frontend

- Angular
- TypeScript
- HTML
- SCSS
- Bootstrap 5
- Bootstrap Icons
- SweetAlert2
- RxJS

## Backend

- PHP
- MariaDB
- Composer
- PHPMailer

---

# 🏗 Arquitectura

El backend fue desarrollado utilizando una arquitectura por capas.

```
public/
src/
 ├── aplicacion/
 ├── dominio/
 ├── infraestructura/
 └── interface/
```

Las principales responsabilidades de cada capa son:

- **Aplicación:** lógica de negocio.
- **Dominio:** entidades y contratos.
- **Infraestructura:** base de datos, configuración y persistencia.
- **Interface/API:** controladores y DTOs.

---

# 🚀 Instalación

## Clonar el repositorio

```bash
git clone https://github.com/17Franco/sistema-reservas-barberia.git
```

---

## Backend

Instalar dependencias

```bash
composer install
```

Crear la base de datos

```
barberia
```

Importar

```
barberia.sql
```

Crear

```
ParametrosConexion.php (Basandose en el archivo de ejemplo)
```

Configurar
- Servidor (usar los del archivo de ejemplo)
- Usuario
- Contraseña
- Base de datos 
- Email (usar los del archivo de ejemplo)
- Email pass (usar los del archivo de ejemplo)

---

## Frontend

Instalar dependencias

```bash
npm install
```

Ejecutar

```bash
ng serve
```

Abrir

```
http://localhost:4200
```

---

# 📡 API REST

## 👤 Usuarios / Autenticación

| Método | Endpoint | Descripción |
|---------|----------|-------------|
| POST | `/usuarios` | Registrar un nuevo cliente |
| POST | `/login` | Iniciar sesión |
| POST | `/logout` | Cerrar sesión |
| GET | `/me` | Obtener el usuario autenticado |
| POST | `/editarPerfil` | Editar información del perfil |
| GET | `/usuarios/validar-email?email=` | Verificar disponibilidad de un correo |
| GET | `/usuarios/validar-ci?ci=` | Verificar disponibilidad de una cédula |

---

## 💈 Empleados

| Método | Endpoint | Descripción |
|---------|----------|-------------|
| GET | `/empleados` | Obtener todos los empleados |
| POST | `/empleados` | Registrar un empleado |
| GET | `/empleados/{id}/servicios` | Obtener servicios asignados a un empleado |
| PUT | `/empleados/{id}/servicios` | Actualizar servicios asignados |
| PUT | `/empleados/{id}` | Actualizar información de un empleado |
| PUT | `/empleados/estado` | Cambiar el estado de un empleado |

---

## ✂️ Servicios

| Método | Endpoint | Descripción |
|---------|----------|-------------|
| GET | `/servicios` | Obtener todos los servicios |
| POST | `/servicios` | Crear un servicio |
| GET | `/servicios/{id}` | Obtener un servicio por ID |
| PUT | `/servicios/{id}` | Actualizar un servicio |
| DELETE | `/servicios/{id}` | Eliminar un servicio |

---

## 📅 Disponibilidad

| Método | Endpoint | Descripción |
|---------|----------|-------------|
| GET | `/disponibilidad` | Obtener disponibilidad de los próximos 30 días |
| GET | `/servicio/disponibilidad?fecha=` | Consultar servicios disponibles para una fecha |
| GET | `/empleado/disponibilidad?fecha={fecha}&id={servicio}` | Obtener barberos disponibles para un servicio |
| GET | `/disponibilidadHorarios?fecha={fecha}&id={servicio}&idE={empleado}` | Obtener horarios disponibles |

---

## 📖 Reservas

| Método | Endpoint | Descripción |
|---------|----------|-------------|
| POST | `/reservas` | Crear una reserva |
| GET | `/reservas` | Consultar reservas con filtros |
| GET | `/reservas/ClienteAsociado/{id}` | Obtener reservas de un cliente |
| GET | `/reservas/BarberoAsociado/{id}` | Obtener reservas de un barbero |
| POST | `/reservas/{id}/enviar-comprobante` | Enviar comprobante por correo |
| PUT | `/reservas/{id}/cancelar` | Cancelar una reserva |
| PUT | `/reservas/{id}/confirmar` | Confirmar una reserva |
| PUT | `/reservas/{id}/completar` | Marcar una reserva como completada |

---

## ⭐ Reseñas

| Método | Endpoint | Descripción |
|---------|----------|-------------|
| GET | `/resenas` | Obtener todas las reseñas |
| POST | `/resenas` | Registrar una reseña |
| DELETE | `/resenas/{id}` | Eliminar una reseña |

---

# 📂 Estructura del proyecto

```
Sistema-Reservas-Barberia

│
├── BarberiaFrontend
│
├── ProyectoBarberiaBackend
│
├── barberia.sql
│
└── README.md
```

---

# 🎯 Funcionalidades principales

- Gestión completa de reservas
- Gestión de disponibilidad
- Gestión de empleados
- Gestión de servicios
- Gestión de usuarios
- Envío de comprobantes por correo
- Panel administrativo
- Sistema de autenticación
- Protección de rutas
- Gestión de sesiones

---

# 🔒 Roles

## Cliente

- Reservar turnos
- Cancelar reservas
- Ver historial
- Dejar reseñas

## Empleado

- Confirmar reservas
- Completar reservas
- Consultar agenda

## Administrador

- Gestionar usuarios
- Gestionar servicios
- Gestionar empleados
- Gestionar reservas
- Gestionar reseñas

---

# 📈 Mejoras futuras

- Recuperación de contraseña
- Notificaciones por WhatsApp
- Reasignación automática de reservas
- Notificaciones automáticas de cambios de reserva

---

# 👥 Equipo

- Franco Echaide
- Juan Pablo Rodríguez
- Santiago Santos
- Juan Pablo Fontes
- Santiago Guadalupe

---

