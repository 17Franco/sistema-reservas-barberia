<?php

namespace Barberia\Backend\infraestructura;

use Barberia\Backend\aplicacion\impl\ServicioImpl;
use Barberia\Backend\aplicacion\Servicios;

use Barberia\Backend\aplicacion\impl\EmpleadoServiciosImpl;
use Barberia\Backend\aplicacion\EmpleadoServicios;

use Barberia\Backend\infraestructura\persistencia\Conectar;
use Barberia\Backend\infraestructura\persistencia\RepoImpl;
use Barberia\Backend\infraestructura\persistencia\RepoEmpleadoImpl;

class Fabrica {

    //USUARIOS
    public static function crearServicio(): Servicios {

        $conn = new Conectar();
        $repo = new RepoImpl($conn->conectar());

        return new ServicioImpl($repo);
    }

    //EMPLEADOS
    public static function crearEmpleadoServicio(): EmpleadoServicios {

        $conn = new Conectar();
        $repo = new RepoEmpleadoImpl($conn->conectar());

        return new EmpleadoServiciosImpl($repo);
    }
}