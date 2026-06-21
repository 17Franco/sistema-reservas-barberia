<?php
    // se calcula desde pos del archivo actual
    //../ baja carpeta una ves 
    namespace Barberia\Backend\aplicacion;


    use Barberia\Backend\dominio\Usuario;
    use Barberia\Backend\dominio\Cliente;
    use Barberia\Backend\dominio\Empleado;

    interface ServiciosUsuarios {

        //metodos USUARIO
        public function agregarUsuario(Cliente $usu,?array $foto = null): bool;
       
        //verifico credenciales para el login
        public function verificoCredenciales(string $ci,string $pass): ?Cliente;

        public function listarEmpleados(): array;

        public function agregarEmpleado(array $datos): bool;

        public function actualizarEmpleado(int $idEmpleado, array $datos): bool;
      
        //edita los datos basicos del usuario logueado
        public function editarUsuario(int $idUsuario, string $nombre, string $apellido, string $celular, ?string $direccion, ?array $foto = null): ?string;

        public function cambiarEstadoEmpleado(string $ci, string $nuevoEstado): bool;

        public function validarEmail(string $email):bool;
        public function validarCi(string $ci):bool;
    }
        
?>
