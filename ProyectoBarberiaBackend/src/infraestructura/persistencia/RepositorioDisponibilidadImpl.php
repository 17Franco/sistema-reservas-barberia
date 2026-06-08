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
use Barberia\Backend\dominio\ServicioBarberia;
use DateTime;
use LDAP\Result;
use mysqli;


    class RepositorioDisponibilidadImpl implements RepositorioDisponibilidad{
        
        private mysqli $conn;

        public function __construct(mysqli $conn) {
            $this->conn = $conn;
        }

        public function listarServicios(): array{
            $servicios =[];
            return $servicios;
        }

        public function empleadosPorServicio(ServicioBarberia $servicio):array{
            $empleados =[];
            return $empleados;
        }

    }
?>