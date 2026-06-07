<?php

namespace Barberia\Backend\infraestructura;

use Barberia\Backend\aplicacion\impl\ServicioImpl;
use Barberia\Backend\aplicacion\Servicios;

use Barberia\Backend\aplicacion\impl\EmpleadoServiciosImpl;
use Barberia\Backend\aplicacion\EmpleadoServicios;
use Barberia\Backend\aplicacion\impl\ServicioUsuarioImpl;
use Barberia\Backend\aplicacion\ServiciosUsuarios;
use Barberia\Backend\infraestructura\persistencia\Conectar;
use Barberia\Backend\infraestructura\persistencia\RepoEmpleadoImpl;
use Barberia\Backend\infraestructura\persistencia\RepoUsuarioImpl;

class Fabrica {

    //USUARIOS
    public static function crearServicio(): ServiciosUsuarios {

        $conn = new Conectar();
        $repo = new RepoUsuarioImpl($conn->conectar());

        return new ServicioUsuarioImpl($repo);
    }

    //EMPLEADOS
    public static function crearEmpleadoServicio(): EmpleadoServicios {

        $conn = new Conectar();
        $repo = new RepoEmpleadoImpl($conn->conectar());

        return new EmpleadoServiciosImpl($repo);
    }
}