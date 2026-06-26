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
use Sabberworm\CSS\Value\Value;

    class ServicioUsuarioImpl implements ServiciosUsuarios {

        //variable donde guardo la interfaz repo
        private RepositorioUsuario $repo;

        //Inyección por constructo
        public function __construct(RepositorioUsuario $repo) {
            
            $this->repo = $repo;
        }
        
        
        public function agregarUsuario(Cliente $usu,?array $foto = null): bool{

            if($this->repo->existeClientePorCi($usu->getCi()) || $this->repo->emailUsado($usu->getEmail())){
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

        public function editarUsuario(int $idUsuario, string $nombre, string $apellido, string $celular, ?string $direccion, ?array $foto = null): ?string{
            $rutaFoto = null;

            if ($foto !== null && $foto['error'] !== UPLOAD_ERR_NO_FILE) {
                $ci = $this->repo->obtenerCiPorId($idUsuario);

                if ($ci === null) {
                    throw new Exception("El usuario no existe", 404);
                }

                $rutaFoto = $this->guardarFotoPerfil($ci, $foto);
            }

            $ok = $this->repo->editarUsuario(
                $idUsuario,
                $nombre,
                $apellido,
                $celular,
                $direccion,
                $rutaFoto
            );

            if (!$ok) {
                throw new Exception("No se pudo actualizar el perfil", 500);
            }

            return $rutaFoto;
        }

        public function guardarFotoPerfil(string $ci, array $foto): ?string {
            if (!isset($foto["error"]) || $foto["error"] !== UPLOAD_ERR_OK) {
                throw new Exception("No se recibió correctamente la foto de perfil", 400);
            }

            if ($foto["size"] > 5 * 1024 * 1024) {
                throw new Exception("La foto no puede superar los 5 MB", 400);
            }

            $uploads = __DIR__ . "/../../../public/uploads";
            $carpetaUsuario = $uploads . "/" . $ci;

            if (!file_exists($uploads)) {
                if (!mkdir($uploads, 0777, true)) {
                    throw new Exception("No se pudo crear la carpeta uploads", 500);
                }
            }

            if (!is_writable($uploads)) {
                throw new Exception("La carpeta uploads no tiene permisos de escritura", 500);
            }

            if (!file_exists($carpetaUsuario)) {
                if (!mkdir($carpetaUsuario, 0777, true)) {
                    throw new Exception("No se pudo crear la carpeta del usuario", 500);
                }
                }

                if (!is_writable($carpetaUsuario)) {
                    throw new Exception("La carpeta del usuario no tiene permisos de escritura", 500);
                }

                $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($foto["tmp_name"]);
                $formatosPermitidos = [
                    "image/jpeg" => "jpg",
                    "image/png" => "png",
                    "image/webp" => "webp"
                ];

                if (!isset($formatosPermitidos[$mime])) {
                    throw new Exception("Formato de imagen no permitido", 400);
                }

                $extension = $formatosPermitidos[$mime];

                foreach (glob($carpetaUsuario . "/fotoPerfil*") ?: [] as $fotoAnterior) {
                    if (is_file($fotoAnterior)) {
                        unlink($fotoAnterior);
                    }
                }

                // El nombre cambia para evitar que el navegador muestre la foto anterior desde caché.
                $nombreImg = "fotoPerfil_" . time() . "." . $extension;
                $rutaFinal = $carpetaUsuario . "/" . $nombreImg;

                if (!move_uploaded_file($foto["tmp_name"], $rutaFinal)) {
                    throw new Exception("No se pudo guardar la foto de perfil", 500);
                }

                return "/uploads/" . $ci . "/" . $nombreImg;
    }
    
        public function verificoCredenciales(string $ci,string $pass): ?Cliente{

            $usuario = $this->repo->verificar($ci,$pass);
            if($usuario === null){
                throw new Exception("Usuario o contraseña incorrecta",401);
            }
            //var_dump($usuario->getTipo());
            //var_dump(!$this->repo->existeEmpleadoActivo($usuario->getId()));
            if ($usuario->getTipo()->name === 'EMPLEADO' && !$this->repo->existeEmpleadoActivo($usuario->getId())) {
                throw new Exception("El empleado se encuentra inactivo", 403);
            }
            
            return $usuario ;
        }

        public function listarEmpleados(): array {
        return $this->repo->listarEmpleado();
    }

        public function agregarEmpleado(array $datos): bool {
            if ($this->repo->existeClientePorCi($datos['ci']) || $this->repo->emailUsado($datos['email'])) {
                throw new Exception("La cédula o el email ya existen", 409);
            }
            return $this->repo->guardarEmpleado($datos);
        }

        public function actualizarEmpleado(int $idEmpleado, array $datos): bool {
            return $this->repo->actualizarEmpleado($idEmpleado, $datos);
        }

        public function cambiarEstadoEmpleado(string $ci, string $nuevoEstado): bool {
            // Aquí puedes meter reglas de negocio si quisieras en el futuro, 
            // por ahora va directo al grano:
            return $this->repo->cambiarEstadoEmpleado($ci, $nuevoEstado);
        }

        public function validarEmail(string $email):bool{
            if($email !=""){
             return $this->repo->emailUsado($email);
            }
            return false;
        }
        
        public function validarCi(string $ci):bool{
            if($ci !=""){
             return $this->repo->existeClientePorCi($ci);
            }
            return false;
        }

        public function listarServiciosEmpleado(int $idEmpleado): array {
            if (!$this->repo->existeEmpleado($idEmpleado)) {
                throw new Exception("El barbero no existe", 404);
            }

            return $this->repo->listarServiciosEmpleado($idEmpleado);
        }

        public function actualizarServiciosEmpleado(int $idEmpleado, array $servicios): bool {
            if (!$this->repo->existeEmpleado($idEmpleado)) {
                throw new Exception("El barbero no existe", 404);
            }

            foreach ($servicios as $idServicio) {
                if (!is_numeric($idServicio) || (int)$idServicio <= 0) {
                    throw new Exception("Lista de servicios inválida", 400);
                }
            }

            return $this->repo->actualizarServiciosEmpleado($idEmpleado, array_map('intval', $servicios));
        }
    }



?>
