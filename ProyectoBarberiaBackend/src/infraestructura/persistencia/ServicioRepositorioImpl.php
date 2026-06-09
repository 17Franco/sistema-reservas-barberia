<?php

namespace Barberia\Backend\infraestructura\persistencia;

class ServicioRepositorioImpl {

    private function conectar(): \mysqli {
        $conexion = new \mysqli(SERVIDOR, USUARIO, CONTRASEÑA, BASEDATOS);

        if ($conexion->connect_error) {
            throw new \Exception("Error de conexión a la base de datos", 500);
        }

        $conexion->set_charset("utf8");
        return $conexion;
    }

    public function listarServicios(): array {
        $conexion = $this->conectar();

        $sql = "SELECT idServicio, nombre, descripcion, duracion, precio FROM servicios";
        $resultado = $conexion->query($sql);

        $servicios = [];

        while ($fila = $resultado->fetch_assoc()) {
            $servicios[] = [
                "idServicio" => (int)$fila["idServicio"],
                "nombre" => $fila["nombre"],
                "descripcion" => $fila["descripcion"],
                "duracion" => (int)$fila["duracion"],
                "precio" => (float)$fila["precio"]
            ];
        }

        $conexion->close();

        return $servicios;
    }

    public function buscarServicio(int $idServicio): ?array {
        $conexion = $this->conectar();

        $sql = "SELECT idServicio, nombre, descripcion, duracion, precio 
                FROM servicios 
                WHERE idServicio = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idServicio);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            $stmt->close();
            $conexion->close();
            return null;
        }

        $fila = $resultado->fetch_assoc();

        $servicio = [
            "idServicio" => (int)$fila["idServicio"],
            "nombre" => $fila["nombre"],
            "descripcion" => $fila["descripcion"],
            "duracion" => (int)$fila["duracion"],
            "precio" => (float)$fila["precio"]
        ];

        $stmt->close();
        $conexion->close();

        return $servicio;
    }

    public function crearServicio(string $nombre, string $descripcion, int $duracion, float $precio): bool {
        $conexion = $this->conectar();

        $sql = "INSERT INTO servicios (nombre, descripcion, duracion, precio)
                VALUES (?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssid", $nombre, $descripcion, $duracion, $precio);

        $ok = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ok;
    }

    public function actualizarServicio(int $idServicio, string $nombre, string $descripcion, int $duracion, float $precio): bool {
        $conexion = $this->conectar();

        $sql = "UPDATE servicios
                SET nombre = ?, descripcion = ?, duracion = ?, precio = ?
                WHERE idServicio = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssidi", $nombre, $descripcion, $duracion, $precio, $idServicio);

        $stmt->execute();

        $ok = $stmt->affected_rows > 0;

        $stmt->close();
        $conexion->close();

        return $ok;
    }

    public function eliminarServicio(int $idServicio): bool {
        $conexion = $this->conectar();

        $sql = "DELETE FROM servicios WHERE idServicio = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idServicio);

        $stmt->execute();

        $ok = $stmt->affected_rows > 0;

        $stmt->close();
        $conexion->close();

        return $ok;
    }
}
?>