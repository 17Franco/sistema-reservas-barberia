<?php
    // se calcula desde pos del archivo actual
    //../ baja carpeta una ves 
    namespace Barberia\Backend\aplicacion;


    interface ServiciosDisponibilidad {

        //diasDiponibles devuelve una lista de fecha en las que todavia entran reservas
        public function disponibilidadDias(): array;

        
       
    }
?>