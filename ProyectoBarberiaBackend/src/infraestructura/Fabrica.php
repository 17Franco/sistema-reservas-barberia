<?php

namespace Barberia\Backend\infraestructura;

use Barberia\Backend\aplicacion\impl\ServicioImpl;
use Barberia\Backend\aplicacion\Servicios;

use Barberia\Backend\aplicacion\impl\EmpleadoServiciosImpl;
use Barberia\Backend\aplicacion\EmpleadoServicios;
use Barberia\Backend\aplicacion\impl\ServiciosDisponibilidadImpl;
use Barberia\Backend\aplicacion\impl\ServicioUsuarioImpl;
use Barberia\Backend\aplicacion\ServiciosDisponibilidad;
use Barberia\Backend\aplicacion\ServiciosUsuarios;
use Barberia\Backend\infraestructura\persistencia\Conectar;
use Barberia\Backend\infraestructura\persistencia\RepositorioDisponibilidadImpl;
use Barberia\Backend\infraestructura\persistencia\RepoUsuarioImpl;

class Fabrica {

    //USUARIOS
    public static function crearServicio(): ServiciosUsuarios {

        $conn = new Conectar();
        $repo = new RepoUsuarioImpl($conn->conectar());

        return new ServicioUsuarioImpl($repo);
    }

    //ServicioDisponibilidad
    public static function crearDisponinilidadServicios(): ServiciosDisponibilidad {

        $conn = new Conectar();
        $repo = new RepositorioDisponibilidadImpl($conn->conectar());

        return new ServiciosDisponibilidadImpl($repo);
    }
}