<?php
    namespace Barberia\Backend\dominio\repositorio;

    use Barberia\Backend\dominio\Cliente;
    use Barberia\Backend\dominio\Empleado;
    use Barberia\Backend\dominio\Usuario;

    interface RepositorioUsuario {
        public function guardarCliente(Cliente $u): bool;

        public function existeClientePorCi(string $ci): bool;
        
        public function existeClientePorId(int $id): bool;

        public function existeEmpleado(int $id): bool;

        public function emailUsado(string $email): bool;

        public function getEmailById(int $idUsuario):?string;

        public function verificar(string $ci, string $pass): ?Cliente;
        
        public function actualizar(Usuario $usuario): bool;

        public function eliminar(string $id): bool;

        public function obtenerCliente(string $id): ?Usuario;

        public function listar(): array;

        public function listarEmpleado(): array;

        public function guardarEmpleado(array $datos): bool;

        public function actualizarEmpleado(int $idEmpleado, array $datos): bool;

        public function obtenerEmpleado(string $ci): ?Empleado;

        public function editarUsuario(int $idUsuario, string $nombre, string $apellido, string $celular, ?string $direccion, ?string $foto): bool;

        public function obtenerCiPorId(int $idUsuario): ?string;

        public function cambiarEstadoEmpleado(string $ci, string $nuevoEstado): bool;

          
    }
?>
