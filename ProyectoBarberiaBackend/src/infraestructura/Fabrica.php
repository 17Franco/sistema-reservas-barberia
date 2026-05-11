<?php

    namespace Barberia\Backend\infraestructura;

    use Barberia\Backend\aplicacion\impl\ServicioImpl;
    use Barberia\Backend\aplicacion\Servicios;
    use Barberia\Backend\infraestructura\persistencia\Conectar;
    use Barberia\Backend\infraestructura\persistencia\RepoImpl;

    class Fabrica {

        //intento de fabrica
        public static function crearServicio(): Servicios {
            $conn = new Conectar();
            $repo = new RepoImpl($conn->conectar());
            
            return new ServicioImpl($repo);
        }
    }
?>