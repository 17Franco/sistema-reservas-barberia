<?php

namespace Barberia\Backend\dominio\repositorio;

Interface RepositorioResena {

    public function listarResenas(): array;

    public function crearResena(int $idCliente, int $idEmpleado, int $puntuacion, ?string $comentario): bool;

    public function eliminarResena(int $idResena): bool;
}

?>