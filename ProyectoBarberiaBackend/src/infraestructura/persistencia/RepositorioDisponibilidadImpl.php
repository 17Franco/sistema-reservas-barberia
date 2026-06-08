<?php
namespace Barberia\Backend\infraestructura\persistencia;

use ArrayObject;
use Barberia\Backend\dominio\repositorio\Repositorio;
use Barberia\Backend\dominio\repositorio\RepositorioUsuario;
use Barberia\Backend\dominio\TipoUsuario;
use Barberia\Backend\dominio\Usuario;
use Barberia\Backend\dominio\Cliente;
use Barberia\Backend\dominio\Empleado;
use Barberia\Backend\dominio\EstadoEmpleado;
use Barberia\Backend\dominio\repositorio\RepositorioDisponibilidad;
use DateTime;
use LDAP\Result;
use mysqli;


    class RepositorioDisponibilidadImpl implements RepositorioDisponibilidad{
        
        private mysqli $conn;

        public function __construct(mysqli $conn) {
            $this->conn = $conn;
        }

        
      

    }
?>