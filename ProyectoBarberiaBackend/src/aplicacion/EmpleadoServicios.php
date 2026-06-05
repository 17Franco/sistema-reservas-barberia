<?php

namespace Barberia\Backend\aplicacion;

use Barberia\Backend\dominio\Empleado;

interface EmpleadoServicios {

    public function listarEmpleados(): array;

    public function actualizarEmpleado(Empleado $e): bool;
}