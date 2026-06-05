<?php

namespace Barberia\Backend\dominio\repositorio;

use Barberia\Backend\dominio\Empleado;

interface RepositorioEmpleado {

    public function listar(): array;

    public function actualizar(Empleado $empleado): bool;

    public function buscarPorCi(string $ci): ?Empleado;
}