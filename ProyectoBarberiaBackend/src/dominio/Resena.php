<?php

namespace Barberia\Backend\dominio;

class Resena {

    private ?int $idResena;
    private int $idReserva;
    private int $idCliente;
    private int $puntuacion;
    private ?string $comentario;
    private ?string $fechaCreacion;

    public function __construct(
        int $idReserva,
        int $idCliente,
        int $puntuacion,
        ?string $comentario = null,
        ?int $idResena = null,
        ?string $fechaCreacion = null
    ) {
        $this->idResena = $idResena;
        $this->idReserva = $idReserva;
        $this->idCliente = $idCliente;
        $this->puntuacion = $puntuacion;
        $this->comentario = $comentario;
        $this->fechaCreacion = $fechaCreacion;
    }

    public function getIdResena(): ?int {
        return $this->idResena;
    }

    public function getIdReserva(): int {
        return $this->idReserva;
    }

    public function getIdCliente(): int {
        return $this->idCliente;
    }

    public function getPuntuacion(): int {
        return $this->puntuacion;
    }

    public function getComentario(): ?string {
        return $this->comentario;
    }

    public function getFechaCreacion(): ?string {
        return $this->fechaCreacion;
    }
}
?>