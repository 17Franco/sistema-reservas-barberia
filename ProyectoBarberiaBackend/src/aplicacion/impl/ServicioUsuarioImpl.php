<?php
namespace Barberia\Backend\aplicacion\impl;

use Barberia\Backend\dominio\Usuario; //esto es como include nesesita el namespace en la clase 
use Barberia\Backend\dominio\Cliente;
use Barberia\Backend\aplicacion\ServiciosUsuarios;
use Barberia\Backend\dominio\Dia;
use Barberia\Backend\dominio\Empleado;
use Barberia\Backend\dominio\repositorio\Repositorio;
use Barberia\Backend\dominio\repositorio\RepositorioUsuario;
use DateTime;
use Exception;


    class ServicioUsuarioImpl implements ServiciosUsuarios {

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

        public function diaInglesToEspanol(string $dia){
            $diasSemana = [
            "Monday" => "Lunes",
            "Tuesday" => "Martes",
            "Wednesday" => "Miércoles",
            "Thursday" => "Jueves",
            "Friday" => "Viernes",
            "Saturday" => "Sábado",
            "Sunday" => "Domingo"
            ];
            return $diasSemana[$dia];
        }
        public function mesInglesToEspanol(string $mes){
            $meses = [
                "January" => "Enero",
                "February" => "Febrero",
                "March" => "Marzo",
                "April" => "Abril",
                "May" => "Mayo",
                "June" => "Junio",
                "July" => "Julio",
                "August" => "Agosto",
                "September" => "Septiembre",
                "October" => "Octubre",
                "November" => "Noviembre",
                "December" => "Diciembre"
            ];
            return $meses[$mes];
        }

        
        public function disponibilidadDia(Dia $dia){
            //debo traer empledos 
            //de cada empleado sus servicios
            //luego las reservas de ese empleado para ese dia
            //me fijo entra un slot listo devuelvo dia diponible no sigo con otro servicio y si no puede ningun servicio con otro empleado y asi 

        }
        public function disponibilidad(): array{
            //genero array de 30 dias 
            $dias = [];
            $hoy = new DateTime();
            $horaActual = $hoy->format("H");
            if($horaActual > 21){
                 $hoy->modify("+1 day");
            }

            for ($i = 0; $i < 30; $i++) {

                $fecha = clone $hoy;
                $fecha->modify("+$i days");

                $dia = new Dia();

                $dia->setFecha($fecha->format("Y-m-d"));
                $dia->setDia($this->diaInglesToEspanol($fecha->format("l")));
                $dia->setNumeroDia((int)$fecha->format("d"));
                $dia->setMes($this->mesInglesToEspanol($fecha->format("F")));
                $dia->setDisponible(false);

                $dias[] = $dia;
            }
            //ahora debo ver si caben mas turnos o no en esos dias osea si ya estan full


            return $dias;
        }

        public function listarEmpleados(): array {
        return $this->repo->listar();
    }

        public function actualizarEmpleado(Empleado $e): bool {
            return $this->repo->actualizar($e);
        }
    }

?>