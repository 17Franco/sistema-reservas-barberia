<?php

namespace Barberia\Backend\interface\api\controllers;

use Barberia\Backend\aplicacion\ServicioResena;
use Exception;

class ResenaController {

public static function listarResenas(ServicioResena $servicio): void {
    $resenas = $servicio->listarResenas();

    http_response_code(200);
    echo json_encode([
        "success" => true,
        "resenas" => $resenas
    ]);
}

public static function crearResena(ServicioResena $servicio): void {
    session_start();

    if (!isset($_SESSION['usuario_id'])) {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "error" => "No hay sesión activa"
        ]);
        return;
    }

    $idCliente = $_SESSION['usuario_id'];

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['idEmpleado']) || !isset($data['puntuacion'])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "error" => "Faltan datos"
        ]);
        return;
    }

    $idEmpleado = (int)$data['idEmpleado'];
    $puntuacion = (int)$data['puntuacion'];
    $comentario = $data['comentario'] ?? null;

    $ok = $servicio->crearResena($idCliente, $idEmpleado, $puntuacion, $comentario);

    http_response_code(201);
    echo json_encode([
        "success" => $ok,
        "message" => "Reseña creada correctamente"
    ]);
}

    public static function eliminarResena(ServicioResena $servicio, int $idResena): void {
        $ok = $servicio->eliminarResena($idResena);

        if (!$ok) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "error" => "Reseña no encontrada"
            ]);
            return;
        }

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Reseña eliminada correctamente"
        ]);
    }
}
?>