<?php
    namespace Barberia\Backend\dominio\repositorio;

    use Barberia\Backend\dominio\Cliente;
    use Barberia\Backend\dominio\Empleado;
    use Barberia\Backend\dominio\Usuario;

    interface RepositorioUsuario {
        public function guardarCliente(Cliente $u): bool;

        public function existe(string $ci): bool;

        public function emailUsado(string $email): bool;

        public function verificar(string $ci, string $pass): ?Cliente;
        
        public function actualizar(Usuario $usuario): bool;

        public function eliminar(string $id): bool;

        public function buscarPorId(string $id): ?Usuario;

        public function listar(): array;

        public function listarEmpleado(): array;

        public function actualizarEmpleado(Empleado $empleado): bool;

        public function buscarPorCiEmpleado(string $ci): ?Empleado;

        public function editarUsuario(int $idUsuario, string $nombre, string $apellido, string $celular): bool;

        public function cambiarEstadoEmpleado(string $ci, string $nuevoEstado): bool;

          
    }
?>
