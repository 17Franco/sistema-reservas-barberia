<?php
namespace Barberia\Backend\aplicacion\impl;

use Barberia\Backend\dominio\Usuario; //esto es como include nesesita el namespace en la clase 
use Barberia\Backend\dominio\Cliente;
use Barberia\Backend\aplicacion\Servicios;
use Barberia\Backend\dominio\repositorio\Repositorio;
use Barberia\Backend\dominio\repositorio\RepositorioUsuario;
use Exception;


    class ServicioImpl implements Servicios {

        //variable donde guardo la interfaz repo
        private RepositorioUsuario $repo;

        //Inyección por constructo
        public function __construct(RepositorioUsuario $repo) {
            
            $this->repo = $repo;
        }
        
       
        public function agregarUsuario(Cliente $usu,?array $foto = null): bool{

            if($this->repo->existe($usu->getCi()) || $this->repo->emailUsado($usu->getEmail())){
                //lanzo exepcion es agarrada por el catch del index
               throw  new Exception("Usuario ya Existe",409); 
            }
            //antes de guardar debo manejar la fotoPerfil si mando

            if($foto != null){
                //lugar donde quiero guardar la img  formato seria upload/nombreUsuario/lafoto.extencion
                $rutaImg = $this->guardarFotoPerfil($usu->getCi(),$foto) ;

                $usu->setFoto($rutaImg);
            }

            //llamo al repo
            return $this->repo->guardarCliente($usu); 
        }

        public function guardarFotoPerfil(string $ci,array $foto){
            $uploads = __DIR__ . "/../../../public/uploads";
            $carpetaUsuario = $uploads . "/" . $ci;//donde quiero crear o si existe guardar la img
            $rutaAdevolver=null;
            $existe=false;
            if(file_exists($uploads)){
                if(file_exists($carpetaUsuario)){
                    $existe=true;
                }

                if(!$existe){
                    mkdir($carpetaUsuario);//si no existe creo la carpeta donde guardare la fotoPerfil
                }

                $extencion =pathinfo($foto["name"], PATHINFO_EXTENSION);
                $nombreImg ="fotoPerfil.". $extencion;//nombre de la img con extencion 
                $rutaFinal = $carpetaUsuario. "/" . $nombreImg;
                move_uploaded_file($foto["tmp_name"],$rutaFinal);
                $rutaAdevolver = "/uploads/" . $ci . "/" . $nombreImg; //
           }
            return $rutaAdevolver;
        }
    
        public function verificoCredenciales(string $ci,string $pass): ?Cliente{
            $usuario = $this->repo->verificar($ci,$pass);
            if($usuario === null){
                throw new Exception("Usuario o contraseña incorrecta",401);
            }
            return $usuario ;
        }
    }

?>