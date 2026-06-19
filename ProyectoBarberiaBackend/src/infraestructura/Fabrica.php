<?php

namespace Barberia\Backend\infraestructura;

use Barberia\Backend\aplicacion\impl\ServicioImpl;
use Barberia\Backend\aplicacion\Servicios;

use Barberia\Backend\aplicacion\impl\EmpleadoServiciosImpl;
use Barberia\Backend\aplicacion\EmpleadoServicios;
use Barberia\Backend\aplicacion\impl\ServicioEmailImpl;
use Barberia\Backend\aplicacion\impl\ServicioPDFImpl;
use Barberia\Backend\aplicacion\impl\ServiciosDisponibilidadImpl;
use Barberia\Backend\aplicacion\impl\ServiciosReservaImpl;
use Barberia\Backend\aplicacion\impl\ServicioUsuarioImpl;
use Barberia\Backend\aplicacion\ServiciosDisponibilidad;
use Barberia\Backend\aplicacion\ServiciosReserva;
use Barberia\Backend\aplicacion\ServiciosUsuarios;
use Barberia\Backend\infraestructura\persistencia\Conectar;
use Barberia\Backend\infraestructura\persistencia\RepositorioDisponibilidadImpl;
use Barberia\Backend\infraestructura\persistencia\RepositorioReservaImpl;
use Barberia\Backend\infraestructura\persistencia\RepoUsuarioImpl;
use Barberia\Backend\infraestructura\persistencia\ServicioRepositorioImpl;

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

    public static function crearReservaServicios(): ServiciosReserva {

        $conn = new Conectar();
        $repoUsuario = new RepoUsuarioImpl($conn->conectar());
        $repoServicio = new ServicioRepositorioImpl($conn->conectar());
        $repoReserva = new RepositorioReservaImpl($conn->conectar());
        $email = new ServicioEmailImpl();
        $pdf = new ServicioPDFImpl();

        return new ServiciosReservaImpl($repoUsuario,$repoServicio,$repoReserva,$email,$pdf);
    }
}