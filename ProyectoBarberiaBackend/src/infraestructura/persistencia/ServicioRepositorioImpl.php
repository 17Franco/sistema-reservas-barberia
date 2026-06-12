<?php

namespace Barberia\Backend\infraestructura\persistencia;
use Barberia\Backend\dominio\EstadoReserva;

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

    //consulto a la reserva primero para conseguir la id del servicio
    public function listarReservasClienteAsociado(int $idCliente) :array{
        $conexion = $this->conectar();
        //pido las reservas del cliente, para acceder a sus servicios reservados
         $sql = "SELECT
                reserva.idReserva,
                reserva.idServicio,
                reserva.estado,
                reserva.fecha,
                reserva.horaInicio,
                servicio.nombre,
                servicio.descripcion,
                servicio.duracion,
                servicio.precio
            FROM reservas reserva
            INNER JOIN servicios servicio ON servicio.idServicio = reserva.idServicio
            WHERE reserva.idCliente = ?
            ORDER BY reserva.fecha DESC, reserva.horaInicio DESC";

        //Como usé "?" para indicarle a SQL que le iba a pasar después el id pues ahora preparo una consulta con ese parametro a la que llamo consultaPreparada
        $consultaPreparada = $conexion->prepare($sql);
        $consultaPreparada->bind_param("i", $idCliente);
        $consultaPreparada->execute();

        //resultado tiene una banda de cosas, tiene idReserva, idServicio, estado de la reserva, hora fin e inicio, toda la info del servicio tambien
        $resultado = $consultaPreparada->get_result();


        $serviciosRealizados = [];
        //los casos en que no hay un tipo detras es porque es string
        while ($fila = $resultado->fetch_assoc()) {
            $serviciosRealizados[] = [
                "idServicio" => (int) $fila ["idServicio"],
                "idReserva" => (int) $fila ["idReserva"],
                "estado" => $fila ["estado"],
                "fecha" => $fila["fecha"],
                "horaInicio" => $fila["horaInicio"],
                "nombre" => $fila["nombre"],
                "descripcion" => $fila["descripcion"],
                "duracion" => (int)$fila["duracion"],
                "precio" => (float)$fila["precio"]
            ];
        }
        
        
        
       
        $consultaPreparada->close();
        $conexion->close();

    return $serviciosRealizados;

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