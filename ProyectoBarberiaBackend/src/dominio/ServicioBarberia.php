<?php
    namespace Barberia\Backend\dominio;
    class ServicioBarberia {

        private int $idServicio;
        private string $nombre;
        private string $descripcion;
        private int $duracion;
        private int $precio;
        private string $disponible;

        public function __construct(int $idServicio,string $nombre,string $descripcion,int $duracion,int $precio) {
            $this->idServicio = $idServicio;
            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
            $this ->duracion = $duracion;
            $this->precio = $precio;
        }

        public function getIdServicio(): int {
            return $this->idServicio;
        }

        public function getDisponible(): int {
            return $this->disponible;
        }

        public function getDuracion(): int {
            return $this->duracion;
        }

        public function setIdServicio(int $idServicio): void {
            $this->idServicio = $idServicio;
        }

        public function setDisponible(string $disponible): void {
            $this->disponible = $disponible;
        }

        public function setIDuracion(int $duracion): void {
            $this->duracion = $duracion;
        }

        public function getNombre(): string {
            return $this->nombre;
        }

        public function setNombre(string $nombre): void {
            $this->nombre = $nombre;
        }

        public function getDescripcion(): string {
            return $this->descripcion;
        }

        public function setDescripcion(string $descripcion): void {
            $this->descripcion = $descripcion;
        }


        public function getPrecio(): int {
            return $this->precio;
        }

        public function setPrecio(int $precio): void {
            $this->precio = $precio;
        }
    }
?>