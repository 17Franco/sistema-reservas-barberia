<?php
    namespace Barberia\Backend\dominio;
    class ServicioBarberia {

        private int $idServicio;
        private string $nombre;
        private string $descripcion;
        private string $img;
        private int $precio;

        public function __construct(int $idServicio,string $nombre,string $descripcion,string $img,int $precio) {
            $this->idServicio = $idServicio;
            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
            $this->img = $img;
            $this->precio = $precio;
        }

        public function getIdServicio(): int {
            return $this->idServicio;
        }

        public function setIdServicio(int $idServicio): void {
            $this->idServicio = $idServicio;
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

        public function getImg(): string {
            return $this->img;
        }

        public function setImg(string $img): void {
            $this->img = $img;
        }

        public function getPrecio(): int {
            return $this->precio;
        }

        public function setPrecio(int $precio): void {
            $this->precio = $precio;
        }
    }
?>