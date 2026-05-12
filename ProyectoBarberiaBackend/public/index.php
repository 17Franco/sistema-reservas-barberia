<?php
    //esto es por composer te da un autoload para buscar paquete con solo agregar esto
    //unica ves que se pone esto luego se usa el use y la ruta al paquete 
    //tambien cada class nesesita el namespace


    //CORS
    //header("Access-Control-Allow-Origin: http://localhost:4200");
    //header("Access-Control-Allow-Credentials: true");
    //header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    //header("Access-Control-Allow-Headers: Content-Type");

    require_once __DIR__ . '/../vendor/autoload.php';

    use Barberia\Backend\infraestructura\Fabrica;
    use Barberia\Backend\interface\api\controllers\UsuarioController;

    header('Content-Type: application/json');

   

    try {
        $service = Fabrica::crearServicio(); //creo servicio utilizando la fabrica se las voy a mandar a los controlladores 

        $method = $_SERVER['REQUEST_METHOD']; //obtengo metodo POST GET UPDATE ETC

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //Quta queryParams

        $base = '/Barberia/ProyectoBarberiaBackend/public/index.php';//ruta basica de los endpoint

        $route = str_replace($base, '', $path);//replazo por basio lo que coincide de base en path
        
        //llamo a la funcion de la clase estatica segun corresponda
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { //este es para CORS 
            http_response_code(200);
            exit;
        }else if ($method === 'POST' && str_contains($route, '/usuarios')) { 
            UsuarioController::registrarUsuario($service);
        }else if($method === 'POST' && str_contains($route, '/login')){
            UsuarioController::login($service);
        }else if($method === 'POST' && str_contains($route, '/logout')){
            UsuarioController::logout();
        }else if($method === 'GET' && str_contains($route, '/probando')){
            UsuarioController::testSesion();
        }

    } catch (Throwable $e) {
        //usa codigo de exepcion si tiene sino manda 500
        http_response_code($e->getCode() ?: 500);

        //devuelvo json con el mensaje del error 
        //pueden ser los que nosotros le pongamos cuando lanzamos exepciones
        echo json_encode([
            "success" => false,
            "error" => $e->getMessage()
        ]);

        exit;
    }

?>