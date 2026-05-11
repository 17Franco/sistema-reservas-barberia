## Instalación y ejecución del proyecto

Este proyecto contiene un frontend desarrollado en Angular y un backend desarrollado en PHP.

Estructura del proyecto:

    sistema-reservas-barberia/
    ├── BarberiaFrontend/
    └── ProyectoBarberiaBackend/
    
## Backend PHP

Entrar a la carpeta del backend:   

cd ProyectoBarberiaBackend

    composer install
    
Este comando instala las dependencias definidas en composer.json y genera la carpeta vendor/, la cual no se sube al repositorio.

Luego configurar los datos de conexión a la base de datos si corresponde.

## Frontend Angular

Entrar a la carpeta del frontend:

    npm install
    
Este comando instala las dependencias definidas en package.json y genera la carpeta node_modules/, la cual no se sube al repositorio.

Ejecutar el servidor de desarrollo:

    ng serve -o
