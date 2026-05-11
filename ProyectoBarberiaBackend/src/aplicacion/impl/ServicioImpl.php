<?php
namespace Barberia\Backend\aplicacion\impl;

use Barberia\Backend\dominio\Usuario; //esto es como include nesesita el namespace en la clase 

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
        
        public function agregarUsuario(Usuario $usu): bool{

            if($this->repo->existe($usu->getCi())){
                //lanzo exepcion es agarrada por el catch del index
               throw  new Exception("Usuario ya Existe",409); 
            }
            //llamo al repo
            return $this->repo->guardar($usu); 
        }
    
        public function verificoCredenciales(string $ci,string $pass): ?Usuario{
            $usuario = $this->repo->verificar($ci,$pass);
            if($usuario === null){
                throw new Exception("Usuario o contraseña incorrecta",401);
            }
            return $usuario ;
        }
    }

?>