<?php

namespace Barberia\Backend\aplicacion\impl;

use Barberia\Backend\dominio\repositorio\RepositorioResena;
use Barberia\Backend\infraestructura\persistencia\ResenaRepositorioImpl;
use Barberia\Backend\aplicacion\ServicioResena;
use Exception;

class ServicioResenaImpl implements ServicioResena {

    private RepositorioResena $repo;

    public function __construct() {
        $this->repo = new ResenaRepositorioImpl();
    }

    public function listarResenas(): array {
        return $this->repo->listarResenas();
    }

public function crearResena(int $idCliente, int $idEmpleado, int $puntuacion, ?string $comentario): bool {
    if ($puntuacion < 1 || $puntuacion > 5) {
        throw new Exception("La puntuación debe estar entre 1 y 5", 400);
    }

    return $this->repo->crearResena($idCliente, $idEmpleado, $puntuacion, $comentario);
}

    public function eliminarResena(int $idResena): bool {
        return $this->repo->eliminarResena($idResena);
    }
}
?>