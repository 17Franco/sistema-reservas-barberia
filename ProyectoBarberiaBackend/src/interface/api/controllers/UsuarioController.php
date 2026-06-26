<?php
namespace Barberia\Backend\interface\api\controllers;

use Barberia\Backend\aplicacion\ServiciosUsuarios;
use Barberia\Backend\dominio\Cliente;
use Barberia\Backend\dominio\Empleado;
use Barberia\Backend\dominio\EstadoEmpleado;
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
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class UsuarioController {
    //url para hacer peticiones  http://localhost:8080/sistema-reservas-barberia/ProyectoBarberiaBackend/public/index.php/NombreRecurso

    public static function registrarUsuarioCliente(ServiciosUsuarios $servicio): void{
       $serializer = new Serializer([new BackedEnumNormalizer(),new ObjectNormalizer()],[new JsonEncoder()]);
            // leer body JSON
            $json = json_encode($_POST);
            $foto = null;
            
            //aca try porque quiero intentar y capturar un error expecifico
            try {
            //utilizo Symfony para pasar json al objeto que nesesito
            $usuario = $serializer->deserialize(
                $json,
                Cliente::class,
                'json'
            );

            if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE) {
                $foto = $_FILES["foto"];
            }
           // if(isset($_FILES["foto"])){
               // $foto = $_FILES["foto"];
           // }

            } catch (MissingConstructorArgumentsException $e) {
                //si lanza error lo agarro y lanzo exepcion 
                throw new Exception("faltan campos", 400);
            }


            // llamo a registrarUsuario de mi service 
            $ok = $servicio->agregarUsuario($usuario,$foto);

            //asigno codigo de respuesta http
            http_response_code(201);

            // responder JSON
            echo json_encode([
                "success" => $ok
            ]);   
            
    }

    public static function login(ServiciosUsuarios $servicio){
         // leer body JSON
        $json = file_get_contents('php://input');
        // convertir JSON → array
        $data = json_decode($json, true);

        // validar que venga toda la info del usuario requerida
        if (!isset($data['email']) || !isset($data['contraseña'])) {

            throw new Exception("Faltan campos", 400);

        }
        $email = $data["email"];
        $pass = $data["contraseña"];
        $usuario = $servicio->verificoCredenciales($email,$pass);
        if($usuario !== null){
            session_start(); 
           
            $_SESSION['usuario_id'] = $usuario->getId();
            $_SESSION['usuario_email'] = $email;
            $_SESSION['nombre'] = $usuario->getNombre();
            $_SESSION['apellido'] = $usuario->getApellido();
            $_SESSION['tipoUser'] = $usuario->getTipo()->value;
            $_SESSION['foto'] = $usuario->getFoto();
            $_SESSION['usuario_celular'] = $usuario->getCel();
            $_SESSION['fechaCreacion'] = $usuario->getFechaCreacion();
            $_SESSION['direccion'] = $usuario->getDireccion();
        }

        http_response_code(200);

        // responder JSON
        echo json_encode([
            "success" => $usuario !== null,
            "id" => $usuario->getId(),
            "nombre" => $usuario->getNombre(),
            "tipo" => $usuario->getTipo()->value,
            "fotoPerfil" => $usuario->getFoto(),
            
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
            "usuario_id" => $_SESSION['usuario_id'],
            "nombre" => $_SESSION['nombre'],
            "apellido" => $_SESSION['apellido'],
            "foto" => $_SESSION['foto'],
            "tipo" => $_SESSION['tipoUser'],
            "email" => $_SESSION['usuario_email'],
            "celular" => $_SESSION['usuario_celular'],
            "fechaCreacion" => $_SESSION['fechaCreacion'],
            "direccion" => $_SESSION['direccion'],
        ]);

        } else {    

            echo json_encode([
                "logueado" => false
            ]);
        }
    }

    public static function listarEmpleados(ServiciosUsuarios $servicio): void {
        $data = $servicio->listarEmpleados();
        echo json_encode([
            'success' => true,
            'empleados' => $data
        ]);
    }

    public static function registrarEmpleado(ServiciosUsuarios $servicio): void {
        $data = self::datosEmpleado(true);
        http_response_code(201);
        echo json_encode(['success' => $servicio->agregarEmpleado($data)]);
    }

    public static function actualizarEmpleado(ServiciosUsuarios $servicio, int $id): void {
        echo json_encode(['success' => $servicio->actualizarEmpleado($id, self::datosEmpleado(false))]);
    }

    public static function listarServiciosEmpleado(ServiciosUsuarios $servicio, int $id): void {
        echo json_encode([
            'success' => true,
            'servicios' => $servicio->listarServiciosEmpleado($id)
        ]);
    }

    public static function actualizarServiciosEmpleado(ServiciosUsuarios $servicio, int $id): void {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        if (!isset($data['servicios']) || !is_array($data['servicios'])) {
            throw new Exception("Falta la lista de servicios", 400);
        }

        echo json_encode([
            'success' => $servicio->actualizarServiciosEmpleado($id, $data['servicios'])
        ]);
    }

    private static function datosEmpleado(bool $alta): array {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        if ($alta) {
            $requeridos = ['ci','nombre','apellido','fechaNac','password','email','celular','idEspecialidad','horaIni','horaFin'];
        } else {
            $requeridos = ['nombre','apellido','email','celular','idEspecialidad'];
        }

        foreach ($requeridos as $campo) {
            if (empty($data[$campo])) throw new Exception("Falta el campo $campo", 400);
        }
        if ($alta && !preg_match('/^\d{8}$/', $data['ci'])) {
            throw new Exception("La cédula debe tener exactamente 8 números", 400);
        }
        return $data;
    }

  
  
    public static function editarUsuario(ServiciosUsuarios $servicio): void {
        session_start();

        if (!isset($_SESSION['usuario_id'])) {
            throw new Exception("No hay usuario logueado", 401);
        }

        // El formulario llega como multipart/form-data para poder incluir la foto.
        $data = $_POST;
        $foto = $_FILES['foto'] ?? null;

        if (!isset($data['nombre']) || !isset($data['apellido']) || !isset($data['celular'])) {
            throw new Exception("Faltan campos", 400);
        }

        $nombre = trim($data['nombre']);
        $apellido = trim($data['apellido']);
        $celular = trim($data['celular']);
        $direccion = isset($data['direccion']) ? trim($data['direccion']) : null; //direccion puede ser null si no quiere poner el usuario

        if ($nombre === '' || $apellido === '' || $celular === '') {
            throw new Exception("Los campos nombre, apellido y celular no pueden estar vacios", 400);
        }

        $rutaFotoNueva = $servicio->editarUsuario(
            (int)$_SESSION['usuario_id'],
            $nombre,
            $apellido,
            $celular,
            $direccion,
            $foto
        );

        $fotoFinal = $rutaFotoNueva ?? $_SESSION['foto'];
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido'] = $apellido;
        $_SESSION['usuario_celular'] = $celular;
        $_SESSION['direccion'] = $direccion;
        $_SESSION['foto'] = $fotoFinal;

        echo json_encode([
            "success" => true,
            "usuario" => [
                "nombre" => $nombre,
                "apellido" => $apellido,
                "celular" => $celular,
                "direccion" => $direccion,
                "foto" => $fotoFinal,
            ]
        ]);
    }




    public static function cambiarEstadoEmpleado(ServiciosUsuarios $servicio): void {
        // 1. Leer el JSON del frontend
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        // 2. Validar que vengan los dos datos necesarios
        if (!isset($data['ci']) || !isset($data['estado'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Faltan campos requeridos (ci, estado)"]);
            return;
        }

        // 3. Ejecutar la activación/desactivación a través del servicio
        $ok = $servicio->cambiarEstadoEmpleado($data['ci'], strtoupper($data['estado']));

        // 4. Responder
        http_response_code(200);
        echo json_encode([
            "success" => $ok
        ]);
    }

    public static function validarEmail(ServiciosUsuarios $servicio): void {
        
        if (!isset($_GET['email'])) {
            throw new Exception("No se recibio un email", 400);
        }
        //OBTENGO EL DATO DEL QUERY PARAM
        $email = $_GET['email'];

        $resu = $servicio->validarEmail($email);

        // 4. Responder
        http_response_code(200);
        echo json_encode([
            "existe" => $resu
        ]);
    }

    public static function validarCi(ServiciosUsuarios $servicio): void {
        
        if (!isset($_GET['ci'])) {
            throw new Exception("No se recibio la cedula", 400);
        }
        //OBTENGO EL DATO DEL QUERY PARAM
        $ci = $_GET['ci'];

        $resu = $servicio->validarCi($ci);

        // 4. Responder
        http_response_code(200);
        echo json_encode([
            "existe" => $resu
        ]);
    }
}
?>
