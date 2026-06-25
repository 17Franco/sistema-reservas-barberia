<?php

namespace Barberia\Backend\aplicacion;

interface ServicioResena {

    public function listarResenas(): array;

   public function crearResena(int $idCliente, int $idEmpleado, int $puntuacion, ?string $comentario): bool;

    public function eliminarResena(int $idResena): bool;
}
?>