<?php
    namespace Barberia\Backend\dominio;

    use DateTime;

   class Usuario {

        private string $ci;
        private string $nombre;
        private string $apellido;
        private DateTime $fechaNac;
        private string $pass;
        private string $email;
        private ?string $foto;
        private string $cel;
        private TipoUsuario $tipo;
    
        public function __construct(string $ci,string $nombre,string $apellido,DateTime $fechaNac,string $pass,string $email,string $cel) {
            $this->ci = $ci;
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->fechaNac = $fechaNac;
            $this->pass = $pass;
            $this->email = $email;
            $this->cel = $cel;
            
        }

        public function getCi(): string { return $this->ci; }
        public function getNombre(): string { return $this->nombre; }
        public function getApellido(): string { return $this->apellido; }
        public function getFechaNac(): DateTime { return $this->fechaNac; }
        public function getPass(): string { return $this->pass; }
        public function getEmail(): string { return $this->email; }
        public function getFoto(): ?string { return $this->foto; }
        public function getCel(): string { return $this->cel; }
        public function getTipo(): TipoUsuario { return $this->tipo; }

        public function setCi(string $ci): void { $this->ci = $ci; }
        public function setNombre(string $nombre): void { $this->nombre = $nombre; }
        public function setApellido(string $apellido): void { $this->apellido = $apellido; }
        public function setFechaNac(DateTime $fechaNac): void { $this->fechaNac = $fechaNac; }
        public function setPass(string $pass): void { $this->pass = $pass; }
        public function setEmail(string $email): void { $this->email = $email; }
        public function setFoto(?string $foto): void { $this->foto = $foto; }
        public function setCel(string $cel): void { $this->cel = $cel; }
        public function setTipo(TipoUsuario $tipo): void { $this->tipo = $tipo; }
    }
?>