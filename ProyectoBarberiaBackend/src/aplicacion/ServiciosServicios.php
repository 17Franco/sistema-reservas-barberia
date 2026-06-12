<?php

namespace Barberia\Backend\aplicacion;

use Barberia\Backend\infraestructura\persistencia\ServicioRepositorioImpl;

class ServiciosServicios {

    private ServicioRepositorioImpl $repo;

    public function __construct() {
        $this->repo = new ServicioRepositorioImpl();
    }

    public function listarServicios(): array {
        return $this->repo->listarServicios();
    }

    public function listarReservasClienteAsociado(int $idCliente):array{
        return $this->repo->listarReservasClienteAsociado($idCliente);
    }

    public function buscarServicio(int $idServicio): ?array {
        return $this->repo->buscarServicio($idServicio);
    }

    public function crearServicio(string $nombre, string $descripcion, int $duracion, float $precio): bool {
        return $this->repo->crearServicio($nombre, $descripcion, $duracion, $precio);
    }

    public function actualizarServicio(int $idServicio, string $nombre, string $descripcion, int $duracion, float $precio): bool {
        return $this->repo->actualizarServicio($idServicio, $nombre, $descripcion, $duracion, $precio);
    }

    public function eliminarServicio(int $idServicio): bool {
        return $this->repo->eliminarServicio($idServicio);
    }
}
?>