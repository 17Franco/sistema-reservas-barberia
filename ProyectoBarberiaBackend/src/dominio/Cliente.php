<?php
namespace Barberia\Backend\dominio;

use Barberia\Backend\dominio\Usuario;
use DateTime;

class Cliente extends Usuario {

    
   

    public function __construct(string $ci,string $nombre,string $apellido,DateTime $fechaNac,string $pass,string $email,string $cel,TipoUsuario $tipo,string $horaIni,string $horaFin,EstadoEmpleado $estado) {

        parent::__construct($ci,$nombre,$apellido,$fechaNac,$pass,$email,$cel);
        
    }

}
?>