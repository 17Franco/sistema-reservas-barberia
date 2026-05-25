<?php
    namespace Barberia\Backend\dominio\repositorio;

    use Barberia\Backend\dominio\Usuario;

    interface RepositorioUsuario {
        public function guardarCliente(Usuario $u): bool;

        public function existe(string $ci): bool;

        public function verificar(string $ci, string $pass): ?Usuario;
        
        public function actualizar(Usuario $usuario): bool;

        public function eliminar(string $id): bool;

        public function buscarPorId(string $id): ?Usuario;

        public function listar(): array;
          
    }
?>
