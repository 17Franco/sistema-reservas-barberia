<?php
namespace Barberia\Backend\interface\api\controllers;
use Barberia\Backend\aplicacion\Servicios;
use Barberia\Backend\aplicacion\ServiciosUsuarios;
//import se Symfony
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;//exepcion que lanza cuando faltan campos del contructor
use Symfony\Component\Serializer\Encoder\JsonEncoder;//
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;//
use Symfony\Component\Serializer\Serializer;//
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;//permite entender enum y 
use Exception;



class AvailabilityController {
    
   public static function days(ServiciosUsuarios $servicio):void{
    $serializer = new Serializer([new BackedEnumNormalizer(),new ObjectNormalizer()],[new JsonEncoder()]);
       
        $data = $servicio->disponibilidad();

        http_response_code(200);

         echo $serializer->serialize([
            "success" => true,
            "data" => $data
        ], 'json');
    }
   
}
?>