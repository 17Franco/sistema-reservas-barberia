## Instalación y ejecución del proyecto

Este proyecto contiene un frontend desarrollado en Angular y un backend desarrollado en PHP.

Estructura del proyecto:

    sistema-reservas-barberia/
    ├── BarberiaFrontend/
    └── ProyectoBarberiaBackend/

## Lugar donde clonar

Clonar o descargar el proyecto en:

- Windows (XAMPP):

        C:\xampp\htdocs\
  
- Linux (Apache):

        /var/www/html/

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

## Entornos del frontend

El frontend usa archivos de entorno distintos segun el comando que se ejecute.

Al trabajar localmente con:

    ng serve -o

Angular usa:

    BarberiaFrontend/src/environments/environment.development.ts

Ese archivo apunta al backend local de XAMPP/Apache:

    http://localhost/sistema-reservas-barberia/ProyectoBarberiaBackend/public/index.php

Al generar la version para subir al hosting con:

    ng build

Angular usa:

    BarberiaFrontend/src/environments/environment.ts

Ese archivo apunta al backend publicado:

    https://barbershop.site.je/ProyectoBarberiaBackend/public/index.php

Resumen:

    ng serve  -> environment.development.ts -> backend local
    ng build  -> environment.ts             -> backend publicado

Para publicar el frontend, subir al hosting el contenido de:

    BarberiaFrontend/dist/BarberiaFrontend/browser/

Los archivos deben quedar directamente dentro de `htdocs`, no dentro de una carpeta `browser`.
