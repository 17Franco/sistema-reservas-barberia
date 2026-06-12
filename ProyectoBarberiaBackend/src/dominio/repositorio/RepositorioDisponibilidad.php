<?php
    namespace Barberia\Backend\dominio\repositorio;

    use Barberia\Backend\dominio\Cliente;
    use Barberia\Backend\dominio\Empleado;
use Barberia\Backend\dominio\ServicioBarberia;
use Barberia\Backend\dominio\Usuario;

    interface RepositorioDisponibilidad {
        
        public function listarServicios(): array;

        public function obtenerIdsEmpleadosPorServicio(int $idServicio):array;

        public function horarioEmpleado(int $empleado):array;

       public function reservasPorFechaAEmpleado(string $fecha,int $empleado):array;
       
       public function EmpleadosPorServicio(int $idServicio):array;
          
    }
?>
