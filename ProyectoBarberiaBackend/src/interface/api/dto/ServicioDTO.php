<?php
    namespace Barberia\Backend\interface\api\dto;
class ServicioDTO
{
    public function __construct(
        public int $id,
        public string $nombre
    ) {}
}

?>