<?php

namespace Barberia\Backend\interface\api\controllers;

use Barberia\Backend\infraestructura\Fabrica;
use Barberia\Backend\aplicacion\EmpleadoServicios;
use Barberia\Backend\dominio\Empleado;
use Barberia\dominio\TipoUsuario;
use Barberia\dominio\EstadoEmpleado;
use DateTime;

class EmpleadoController {

    public static function listar(): void {

        $servicio = Fabrica::crearServicioEmpleado();

        $data = $servicio->listarEmpleados();

        echo json_encode([
            "success" => true,
            "empleados" => $data
        ]);
    }

    public static function actualizar(): void {

        $servicio = Fabrica::crearServicioEmpleado();

        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        $empleado = new Empleado(
            $data["ci"],
            $data["nombre"],
            $data["apellido"],
            new DateTime($data["fechaNac"]),
            $data["contraseña"],
            $data["email"],
            $data["celular"],
            TipoUsuario::from($data["tipo"]),
            $data["horaInicio"],
            $data["horaFin"],
            EstadoEmpleado::from($data["estado"])
        );

        $ok = $servicio->actualizarEmpleado($empleado);

        echo json_encode([
            "success" => $ok
        ]);
    }
}