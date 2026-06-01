<?php
    namespace Barberia\Backend\dominio;

    enum TipoUsuario: string {
          case CLIENTE = 'CLIENTE';
          case ADMIN = 'ADMIN';
          case EMPLEADO = 'EMPLEADO';
    }
?>