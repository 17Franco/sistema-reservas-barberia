<?php
    // se calcula desde pos del archivo actual
    //../ baja carpeta una ves 
    namespace Barberia\Backend\aplicacion;


    use Barberia\Backend\dominio\Usuario;
    use Barberia\Backend\dominio\Cliente;


    interface Servicios {

        //metodos USUARIO
        public function agregarUsuario(Cliente $usu,?array $foto = null): bool;
       
        //verifico credenciales para el login
        public function verificoCredenciales(string $ci,string $pass): ?Cliente;

    

    }
?>