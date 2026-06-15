<?php
namespace Barberia\Backend\interface\api\controllers;
use Barberia\Backend\aplicacion\Servicios;
use Barberia\Backend\aplicacion\ServiciosDisponibilidad;
use Barberia\Backend\dominio\Dia;
//import se Symfony
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;//exepcion que lanza cuando faltan campos del contructor
use Symfony\Component\Serializer\Encoder\JsonEncoder;//
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;//
use Symfony\Component\Serializer\Serializer;//
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;//permite entender enum y 
use Exception;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class AvailabilityController {
    
   public static function days(ServiciosDisponibilidad $servicio):void{
    $serializer = new Serializer([new BackedEnumNormalizer(),new ObjectNormalizer()],[new JsonEncoder()]);
       
        $data = $servicio->calendario();

        http_response_code(200);

         echo $serializer->serialize([
            "success" => true,
            "data" => $data
        ], 'json');
    }

    public static function serviciosDiponiblesDia(ServiciosDisponibilidad $servicio):void{
        $serializer = new Serializer([new BackedEnumNormalizer(),new ObjectNormalizer()],[new JsonEncoder()]);
        //$json = json_encode($_POST);
        $dia = $_GET['fecha'];
        
        $data = $servicio->disponibilidadServicios($dia);
        

        http_response_code(200);

         echo $serializer->serialize([
            "success" => true,
            "data" => $data
        ], 'json');
    }
    public static function EmpleadoDiponiblesDia(ServiciosDisponibilidad $servicio):void{
        $serializer = new Serializer([new DateTimeNormalizer(),new BackedEnumNormalizer(),new ObjectNormalizer()],[new JsonEncoder()]);
        //$json = json_encode($_POST);
        $dia = $_GET['fecha'];
        $id = $_GET['id'];
        
        $data = $servicio->barberoServicioDisponiblePorDia($dia,$id);
        

        http_response_code(200);

         echo $serializer->serialize([
            "success" => true,
            "data" => $data
        ], 'json');
    }

    public static function horarioDisponible(ServiciosDisponibilidad $servicio):void{
        $serializer = new Serializer([new DateTimeNormalizer(),new BackedEnumNormalizer(),new ObjectNormalizer()],[new JsonEncoder()]);
        //$json = json_encode($_POST);
        $dia = $_GET['fecha'];
        $id = $_GET['id'];
        $idE = $_GET['idE'];
        
        $data = $servicio->horariosDiponiblesDia($dia,$id,$idE);
        

        http_response_code(200);

         echo $serializer->serialize([
            "success" => true,
            "data" => $data
        ], 'json');
    }
   
}
?>