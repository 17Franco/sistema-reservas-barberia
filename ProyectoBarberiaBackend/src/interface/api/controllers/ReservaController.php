<?php
    namespace Barberia\Backend\interface\api\controllers;

use Barberia\Backend\aplicacion\ServiciosReserva;
use Barberia\Backend\dominio\Reserva;
use Barberia\Backend\dominio\Usuario;
use Exception;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

    class ReservaController{
        public static function reservar(ServiciosReserva $servicio){
            $serializer = new Serializer([new DateTimeNormalizer(),new BackedEnumNormalizer(),new ObjectNormalizer()],[new JsonEncoder()]);
            //$json = json_encode($_POST); esto cuando viene form-data o x-www-form-urlencoded.
            $json = file_get_contents("php://input"); //Content-Type: application/json
            //verifico que un usuario este logueado
            session_start();

            //sino exite usuario logueado lanzo exepcion
            if (!isset($_SESSION["usuario_id"])) {
                throw new Exception("Debes iniciar sesión", 401);
            }

            // El usuario está autenticado
            $usuarioId = $_SESSION["usuario_id"];
            $emailUser = $_SESSION['usuario_email'];
            try {
                //utilizo Symfony para pasar json al objeto que nesesito
                $reserva = $serializer->deserialize(
                    $json,
                    Reserva::class,
                    'json'
                );

                $reserva->setIdCliente($usuarioId);

            } catch (MissingConstructorArgumentsException $e) {
                //si lanza error lo agarro y lanzo exepcion 
                throw new Exception("faltan campos", 400);
            }

            $id = $servicio->reservar($reserva,$emailUser);

            //asigno codigo de respuesta http
            http_response_code(201);

            // responder JSON
            echo json_encode([
                "success" => true,
                "idReserva" => $id
            ]); 
        }

        public static function enviarComprobante(ServiciosReserva $servicio,int $idReserva){
             session_start();

            //sino exite usuario logueado lanzo exepcion
            if (!isset($_SESSION["usuario_id"])) {
                throw new Exception("Debes iniciar sesión", 401);
            }

            // El usuario está autenticado
            $usuarioId = $_SESSION["usuario_id"];
            

            $servicio->enviarEmailComprobante($idReserva,$usuarioId);
            //asigno codigo de respuesta http
            http_response_code(200);

            // responder JSON
            echo json_encode([
                "success" => true
            ]); 

        }

        public static function cancelar(ServiciosReserva $servicio,int $idReserva): void {
            session_start();

            if (!isset($_SESSION["usuario_id"])) {
                throw new Exception("Debes iniciar sesión", 401);
            }

            $idUsuario = (int) $_SESSION["usuario_id"];
            $tipoUsuario = (string) ($_SESSION["tipoUser"] ?? '');

            $servicio->cancelarReserva($idReserva, $idUsuario, $tipoUsuario);

            http_response_code(200);

            echo json_encode([
                "success" => true,
                "mensaje" => "Reserva cancelada correctamente"
            ]);
        }


        public static function confirmar(ServiciosReserva $servicio, int $idReserva): void {
            session_start();
            if (!isset($_SESSION["usuario_id"])) {
                throw new Exception("Debes iniciar sesión", 401);
            }
            $idUsuario = (int) $_SESSION["usuario_id"];
            $tipoUsuario = (string) ($_SESSION["tipoUser"] ?? '');

            $servicio->confirmarReserva($idReserva, $idUsuario, $tipoUsuario);

            http_response_code(200);

            echo json_encode([
                "success" => true,
                "mensaje" => "Reserva confirmada correctamente"
            ]);
        }

        public static function completar(ServiciosReserva $servicio, int $idReserva): void {
            session_start();

            if (!isset($_SESSION["usuario_id"])) {
                throw new Exception("Debes iniciar sesión", 401);
            }

            $idUsuario = (int) $_SESSION["usuario_id"];
            $tipoUsuario = (string) ($_SESSION["tipoUser"] ?? '');

            $servicio->completarReserva($idReserva, $idUsuario, $tipoUsuario);

            http_response_code(200);
            echo json_encode([
                "success" => true,
                "mensaje" => "Reserva completada correctamente"
            ]);
        }

        public static function obtenerReserva(ServiciosReserva $servicioReservas):void{
            $serializer = new Serializer([new DateTimeNormalizer(),new BackedEnumNormalizer(),new ObjectNormalizer()],[new JsonEncoder()]);

            $servicio = $_GET['servicio'] ?? null;
            $estado = $_GET['estado'] ?? null;
            $empleado = $_GET['empleado'] ?? null;
            $fechaDesde = $_GET['fechaDesde'] ?? null;
            $fechaHasta = $_GET['fechaHasta'] ?? null;

            if (!$fechaDesde) {
                $fechaDesde = date('Y-m-d');//si no tiene filtro agrego fecha dia actual porque solo listare las reservas de dia actual en adelante como caso general
            }

            //agrupo
            $filters = [
                'servicio' => $servicio,
                'estado' => $estado,
                'empleado' => $empleado,
                'fechaDesde' => $fechaDesde,
                'fechaHasta' => $fechaHasta,
            ];

            $reservas = $servicioReservas->obtenerReservas($filters);

           
            http_response_code(200);

            echo $serializer->serialize([
                "success" => true,
                "totalReservas" => $reservas['totalReservas'],
                "data" => $reservas['data']
            ], 'json');
        }   

    }
?>
