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

### Posible error al ejecutar `composer install` por primera vez

Si al ejecutar:

    composer install

aparece un error similar a:

    requires ext-dom * -> it is missing from your system

significa que falta instalar o habilitar la extensión XML/DOM de PHP.

En Linux, para PHP 8.3, se puede solucionar instalando:

    sudo apt update
    sudo apt install php8.3-xml

Después verificar que la extensión `dom` esté activa:

    php -m | grep dom

Si devuelve algo como:

    dom
    random

está correcto. Puede aparecer `random` porque contiene la palabra `dom`; lo importante es que aparezca `dom`.

Luego ejecutar nuevamente:

    composer install

O, si se quiere usar el Composer local del proyecto:

    php composer.phar install


### Posible error al subir imágenes en Linux

En Linux puede pasar que el backend no pueda guardar imágenes si la carpeta `uploads` no tiene permisos de escritura.

Para desarrollo local con XAMPP, se puede dar permisos a la carpeta con:

    sudo chmod -R 777 /opt/lampp/htdocs/sistema-reservas-barberia/ProyectoBarberiaBackend/public/uploads

Esto permite que el sistema pueda crear y guardar archivos dentro de `uploads`.

Aclaración: `777` da todos los permisos a todos los usuarios. Para desarrollo en `localhost` es una solución práctica y rápida, pero no es lo recomendado para producción. Con `775` se tiene más control, aunque requiere configurar correctamente el usuario y grupo que usa Apache/XAMPP.

## Archivo `htaccess` para el servidor

El proyecto incluye un archivo llamado `htaccess`, utilizado para la configuración del servidor en el hosting.

Al desplegar el proyecto en el servidor, este archivo también debe subirse junto con los archivos del sitio. En el servidor puede ser necesario que quede con el nombre `.htaccess`, dependiendo de cómo lo maneje el administrador de archivos del hosting.

Para más información sobre este punto, revisar la documentación de despliegue del servidor, donde se incluye una guía de cómo desplegar el proyecto en InfinityFree, que fue el servidor elegido para publicar el sistema.

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
