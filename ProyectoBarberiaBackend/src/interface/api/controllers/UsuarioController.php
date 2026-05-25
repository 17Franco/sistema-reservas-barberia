<?php
namespace Barberia\Backend\interface\api\controllers;
use Barberia\Backend\aplicacion\Servicios;
use Barberia\Backend\dominio\TipoUsuario;
use Barberia\Backend\dominio\Usuario;
use DateTime;
use Exception;

//import se Symfony
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;//exepcion que lanza cuando faltan campos del contructor
use Symfony\Component\Serializer\Encoder\JsonEncoder;//
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;//
use Symfony\Component\Serializer\Serializer;//
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;//permite entender enum y 

class UsuarioController {
    //url para hacer peticiones  http://localhost:8080/sistema-reservas-barberia/ProyectoBarberiaBackend/public/index.php/NombreRecurso

    public static function registrarUsuarioCliente(Servicios $servicio): void{
       $serializer = new Serializer([new BackedEnumNormalizer(),new ObjectNormalizer()],[new JsonEncoder()]);
            // leer body JSON
            $json = file_get_contents('php://input');

            // convertir JSON → array
            // $data = json_decode($json, true);


            //aca try porque quiero intentar y capturar un error expecifico
            try {
            //utilizo Symfony para pasar json al objeto que nesesito
            $usuario = $serializer->deserialize(
                $json,
                Usuario::class,
                'json'
            );

            } catch (MissingConstructorArgumentsException $e) {
                //si lanza error lo agarro y lanzo exepcion 
                throw new Exception("faltan campos", 400);
            }

            // validar que venga toda la info del usuario requerida
            // if (!isset($data['ci']) || !isset($data['nombre']) || !isset($data['apellido']) || !isset($data['fechaNac']) || !isset($data['pass']) || !isset($data['email']) || !isset($data['cel']) || !isset($data['tipo'])) {

               // throw new Exception("Faltan campos", 400);

            // }
            //la fecha del json la transformo en DateTime
            //$fecha = new DateTime($data['fechaNac']);

            // crear dominio
            //$usuario = new Usuario($data['ci'],$data['nombre'],$data['apellido'],$fecha,$data['pass'],$data['email'],$data['cel'],TipoUsuario::from($data['tipo']));

            // llamo a registrarUsuario de mi service 
            $ok = $servicio->agregarUsuario($usuario);

            //asigno codigo de respuesta http
            http_response_code(201);

            // responder JSON
            echo json_encode([
                "success" => $ok
            ]);   
            
    }

    public static function login(Servicios $servicio){
         // leer body JSON
        $json = file_get_contents('php://input');
        // convertir JSON → array
        $data = json_decode($json, true);

        // validar que venga toda la info del usuario requerida
        if (!isset($data['ci']) || !isset($data['contraseña'])) {

            throw new Exception("Faltan campos", 400);

        }
        $ci = $data["ci"];
        $pass = $data["contraseña"];
        $usuario = $servicio->verificoCredenciales($ci,$pass);
        if($usuario !== null){
            session_start(); 
            $_SESSION['usuario_id'] = $ci;
            $_SESSION['nombre'] = $usuario->getNombre();
            $_SESSION['tipoUser'] = $usuario->getTipo(); 
        }

        http_response_code(200);

        // responder JSON
        echo json_encode([
            "success" => $usuario !== null,
            "nombre" => $usuario->getNombre(),
            "tipo" => $usuario->getTipo()
        ]);   

    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        session_destroy();

        http_response_code(200);

        echo json_encode([
            "success" => true,
            "message" => "Sesión cerrada"
        ]);
    }

    

    public static function getSession(): void {

        session_start();

        if(isset($_SESSION['usuario_id'])) {

        echo json_encode([
            "logueado" => true,
            "usuario" => $_SESSION['usuario_id'],
            "nombre" => $_SESSION['nombre'],
            "tipo" => $_SESSION['tipoUser'],
        ]);

        } else {

            echo json_encode([
                "logueado" => false
            ]);
        }
    }
}
?>