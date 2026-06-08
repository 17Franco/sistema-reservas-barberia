<?php
    namespace Barberia\Backend\interface\api\controllers;

    use Barberia\Backend\dominio\ServicioBarberia;

    class ServicioController {

        private static function obtenerServicios(): array {
            return [
                new ServicioBarberia(
                    1,
                    'Corte clásico',
                    'Corte tradicional con acabado preciso y estilo cuidado.',
                    30,
                    1200
                ),
                new ServicioBarberia(
                    2,
                    'Degradado moderno',
                    'Degradado con máquina y tijera para un look actual.',
                    20,
                    1500
                ),
                new ServicioBarberia(
                    3,
                    'Afeitado premium',
                    'Afeitado con toalla caliente, crema y acabado profesional.',
                    25,
                    900
                ),
                new ServicioBarberia(
                    4,
                    'Barba y perfilado',
                    'Perfilado de barba con diseño y mantenimiento de líneas.',
                    45,
                    800
                ),
            ];
        }

        public static function listarServicios(): void {
            $servicios = array_map(function (ServicioBarberia $servicio) {
                return [
                    'idServicio' => $servicio->getIdServicio(),
                    'nombre' => $servicio->getNombre(),
                    'descripcion' => $servicio->getDescripcion(),
                    'duracion' => $servicio->getDuracion(),
                    'precio' => $servicio->getPrecio(),
                ];
            }, self::obtenerServicios());

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'servicios' => $servicios,
            ]);
        }

        public static function buscarServicio(int $idServicio): void {
            $servicios = self::obtenerServicios();
            $servicio = null;

            foreach ($servicios as $item) {
                if ($item->getIdServicio() === $idServicio) {
                    $servicio = $item;
                    break;
                }
            }

            if ($servicio === null) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'error' => 'Servicio no encontrado',
                    'id' => $idServicio,
                ]);
                return;
            }

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'servicio' => [
                    'idServicio' => $servicio->getIdServicio(),
                    'nombre' => $servicio->getNombre(),
                    'descripcion' => $servicio->getDescripcion(),
                    'img' => $servicio->getImg(),
                    'precio' => $servicio->getPrecio(),
                ],
            ]);
        }
    }
?>