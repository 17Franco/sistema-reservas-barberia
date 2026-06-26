<?php
namespace Barberia\Backend\interface\api\dto;
class EmpleadoDTO
{
    public function __construct(
        public int $id,
        public string $nombre
    ) {}
}
?>