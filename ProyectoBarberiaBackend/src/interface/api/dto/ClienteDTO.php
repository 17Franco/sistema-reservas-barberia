<?php
    namespace Barberia\Backend\interface\api\dto;
class ClienteDTO
{
    public function __construct(
        public int $id,
        public string $nombre,
        public string $telefono,
        public string $email,
        public string $imagen
    ) {}
}

?>