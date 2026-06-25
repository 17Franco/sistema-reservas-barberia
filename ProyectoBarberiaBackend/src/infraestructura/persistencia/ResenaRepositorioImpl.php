<?php

namespace Barberia\Backend\infraestructura\persistencia;

use Barberia\Backend\dominio\repositorio\RepositorioResena;

class ResenaRepositorioImpl implements RepositorioResena {

    private function conectar(): \mysqli {
        $conexion = new \mysqli(SERVIDOR, USUARIO, CONTRASEÑA, BASEDATOS);

        if ($conexion->connect_error) {
            throw new \Exception("Error de conexión a la base de datos", 500);
        }

        $conexion->set_charset("utf8");
        return $conexion;
    }

public function listarResenas(): array {
    $conexion = $this->conectar();

    $sql = "
        SELECT 
            r.idResena,
            r.idCliente,
            r.idEmpleado,
            r.puntuacion,
            r.comentario,
            r.fechaCreacion,
            uc.nombre AS clienteNombre,
            uc.apellido AS clienteApellido,
            ub.nombre AS barberoNombre,
            ub.apellido AS barberoApellido
        FROM resenas r
        INNER JOIN usuarios uc ON r.idCliente = uc.id
        INNER JOIN usuarios ub ON r.idEmpleado = ub.id
        ORDER BY r.fechaCreacion DESC
    ";

    $resultado = $conexion->query($sql);

    $resenas = [];

    while ($fila = $resultado->fetch_assoc()) {
        $resenas[] = $fila;
    }

    $conexion->close();

    return $resenas;
}

public function crearResena(int $idCliente, int $idEmpleado, int $puntuacion, ?string $comentario): bool {
    $conexion = $this->conectar();

    $sql = "
        INSERT INTO resenas (idCliente, idEmpleado, puntuacion, comentario)
        VALUES (?, ?, ?, ?)
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iiis", $idCliente, $idEmpleado, $puntuacion, $comentario);

    $resultado = $stmt->execute();

    $stmt->close();
    $conexion->close();

    return $resultado;
}

    public function eliminarResena(int $idResena): bool {
        $conexion = $this->conectar();

        $sql = "DELETE FROM resenas WHERE idResena = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idResena);

        $stmt->execute();

        $ok = $stmt->affected_rows > 0;

        $stmt->close();
        $conexion->close();

        return $ok;
    }
}
?>