<?php
    namespace Barberia\Backend\aplicacion;



    interface ServicioEmail {
        public function enviar(string $destino, string $asunto, string $mensaje): bool;
        public function enviarConAdjunto(string $destino,string $asunto,string $mensaje,string $pdf): bool;
    }
?>