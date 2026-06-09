<?php

namespace Barberia\Backend\interface\api\controllers;

use Barberia\Backend\aplicacion\ServiciosServicios;
use Exception;

class ServicioController {

    public static function listarServicios(ServiciosServicios $servicio): void {
        $servicios = $servicio->listarServicios();

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "servicios" => $servicios
        ]);
    }

    public static function buscarServicio(ServiciosServicios $servicio, int $idServicio): void {
        $servicioEncontrado = $servicio->buscarServicio($idServicio);

        if ($servicioEncontrado === null) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "error" => "Servicio no encontrado"
            ]);
            return;
        }

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "servicio" => $servicioEncontrado
        ]);
    }

    public static function crearServicio(ServiciosServicios $servicio): void {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        if (
            !isset($data["nombre"]) ||
            !isset($data["descripcion"]) ||
            !isset($data["duracion"]) ||
            !isset($data["precio"])
        ) {
            throw new Exception("Faltan campos", 400);
        }

        $ok = $servicio->crearServicio(
            $data["nombre"],
            $data["descripcion"],
            (int)$data["duracion"],
            (float)$data["precio"]
        );

        http_response_code(201);
        echo json_encode([
            "success" => $ok,
            "message" => "Servicio creado correctamente"
        ]);
    }

    public static function actualizarServicio(ServiciosServicios $servicio, int $idServicio): void {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        if (
            !isset($data["nombre"]) ||
            !isset($data["descripcion"]) ||
            !isset($data["duracion"]) ||
            !isset($data["precio"])
        ) {
            throw new Exception("Faltan campos", 400);
        }

        $ok = $servicio->actualizarServicio(
            $idServicio,
            $data["nombre"],
            $data["descripcion"],
            (int)$data["duracion"],
            (float)$data["precio"]
        );

        if (!$ok) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "error" => "Servicio no encontrado o sin cambios"
            ]);
            return;
        }

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Servicio actualizado correctamente"
        ]);
    }

    public static function eliminarServicio(ServiciosServicios $servicio, int $idServicio): void {
        $ok = $servicio->eliminarServicio($idServicio);

        if (!$ok) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "error" => "Servicio no encontrado"
            ]);
            return;
        }

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Servicio eliminado correctamente"
        ]);
    }
}
?>