<?php

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once __DIR__ . '/../vendor/autoload.php';

use Barberia\Backend\infraestructura\Fabrica;
use Barberia\Backend\interface\api\controllers\AvailabilityController;
use Barberia\Backend\interface\api\controllers\UsuarioController;
use Barberia\Backend\interface\api\controllers\ServicioController;
use Barberia\Backend\interface\api\controllers\EmpleadoController;

use Barberia\Backend\aplicacion\ServiciosServicios;

date_default_timezone_set('America/Montevideo');

try {

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    $method = $_SERVER['REQUEST_METHOD'];

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $base = '/sistema-reservas-barberia/ProyectoBarberiaBackend/public/index.php';

    $route = str_replace($base, '', $path);

    if ($route === '') {
        $route = '/';
    }

    // Servicios de aplicación
    $service = Fabrica::crearServicio();
    $disponibilidadService = Fabrica::crearDisponinilidadServicios();
    $servicioServicios = new ServiciosServicios();// la fabrica de adorno

    /*
    |--------------------------------------------------------------------------
    | USUARIOS / AUTH
    |--------------------------------------------------------------------------
    */

    if ($method === 'POST' && $route === '/usuarios') {
        UsuarioController::registrarUsuarioCliente($service);
        exit;
    }

    if ($method === 'POST' && $route === '/login') {
        UsuarioController::login($service);
        exit;
    }

    if ($method === 'POST' && $route === '/logout') {
        UsuarioController::logout();
        exit;
    }

    if ($method === 'GET' && $route === '/me') {
        UsuarioController::getSession();
        exit;
    }

    if ($method === 'PUT' && $route === '/editarPerfil') {
        UsuarioController::editarUsuario($service);
        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | EMPLEADOS
    |--------------------------------------------------------------------------
    */

    if ($method === 'GET' && $route === '/empleados') {
        UsuarioController::listarEmpleados($service);
        exit;
    }

    if ($method === 'PUT' && $route === '/empleados') {
        UsuarioController::actualizarEmpleado($service);
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | SERVICIOS
    |--------------------------------------------------------------------------
    */

    // GET /servicios
    if ($method === 'GET' && $route === '/servicios') {
        ServicioController::listarServicios($servicioServicios);
        exit;
    }

    //GET /reservasClienteAsociado/1
    if (preg_match('#^/reservasClienteAsociado/(\d+)$#', $route, $matches)) {
    $idCliente = (int)$matches[1];
        //le pasas el id del cliente y te retorna sus reservas
        if ($method === 'GET') {
            ServicioController::listarReservasClienteAsociado($servicioServicios, $idCliente);
            exit;
        }
    }






    // POST /servicios
    if ($method === 'POST' && $route === '/servicios') {
        ServicioController::crearServicio($servicioServicios);
        exit;
    }

    // GET /servicios/1
    // PUT /servicios/1
    // DELETE /servicios/1
    if (preg_match('#^/servicios/(\d+)$#', $route, $matches)) {
        $idServicio = (int)$matches[1];

        if ($method === 'GET') {
            ServicioController::buscarServicio($servicioServicios, $idServicio);
            exit;
        }

        if ($method === 'PUT') {
            ServicioController::actualizarServicio($servicioServicios, $idServicio);
            exit;
        }

        if ($method === 'DELETE') {
            ServicioController::eliminarServicio($servicioServicios, $idServicio);
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DISPONIBILIDAD
    |--------------------------------------------------------------------------
    */

    if ($method === 'GET' && $route === '/disponibilidad') {
        AvailabilityController::days($disponibilidadService);
        exit;
    }
    if ($method === 'GET' && $route === '/servicio/disponibilidad') {
        AvailabilityController::serviciosDiponiblesDia($disponibilidadService);
        exit;
    }
    if ($method === 'GET' && $route === '/empleado/disponibilidad') {
        AvailabilityController::EmpleadoDiponiblesDia($disponibilidadService);
        exit;
    }
    if ($method === 'GET' && $route === '/disponibilidadHorarios') {
        AvailabilityController::horarioDisponible($disponibilidadService);
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | SI NO ENCUENTRA RUTA
    |--------------------------------------------------------------------------
    */

    http_response_code(404);
    echo json_encode([
        "success" => false,
        "error" => "Ruta no encontrada",
        "method" => $method,
        "route" => $route
    ]);
    exit;

} catch (Throwable $e) {

    $code = $e->getCode();

    if ($code < 100 || $code > 599) {
        $code = 500;
    }

    http_response_code($code);

    echo json_encode([
        "success" => false,
        "error" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine()
    ]);

    exit;
}

?>
