<?php
    namespace Barberia\Backend\dominio\repositorio;

    use Barberia\Backend\dominio\Cliente;
    use Barberia\Backend\dominio\Empleado;
use Barberia\Backend\dominio\ServicioBarberia;
use Barberia\Backend\dominio\Usuario;

    interface RepositorioDisponibilidad {
        
        public function listarServicios(): array;

        public function empleadosPorServicio(ServicioBarberia $servicio):array;
          
    }
?>
