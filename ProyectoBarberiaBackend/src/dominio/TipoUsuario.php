<?php
    namespace Barberia\Backend\dominio;

    enum TipoUsuario: int {
        case CLIENTE = 1;
        case ADMIN = 2;
        case EMPLEADO = 3;
    }
?>