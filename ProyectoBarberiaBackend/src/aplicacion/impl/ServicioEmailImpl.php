<?php
    namespace Barberia\Backend\aplicacion\impl;

    use Barberia\Backend\aplicacion\ServicioEmail;
    use Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    require_once __DIR__ . '/../../infraestructura/config/ParametrosConexion.php';

    class ServicioEmailImpl implements ServicioEmail {

        public function enviar(string $destino, string $asunto, string $mensaje): bool{
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL;
                $mail->Password = EMAIL_PASS;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('barberia.reservas2026@gmail.com', 'Barberia');
                $mail->addAddress($destino);

                $mail->isHTML(true);
                $mail->Subject = $asunto;
                $mail->Body = $mensaje;

                $mail->send();

                return true;

            } catch (Exception $e) {
                return false;
            }
        }
        
        public function enviarConAdjunto(string $destino,string $asunto,string $mensaje,string $pdf): bool{
             $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL;
                $mail->Password = EMAIL_PASS;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('barberia.reservas2026@gmail.com', 'Barberia');
                $mail->addAddress($destino);

                $mail->isHTML(true);
                $mail->Subject = $asunto;
                $mail->Body = $mensaje;

                $mail->addStringAttachment(
                    $pdf,
                    "comprobante.pdf",
                    "base64",
                    "application/pdf"
                );

                $mail->send();

                return true;

            } catch (Exception $e) {
                 throw new Exception("Error enviando email: " . $e->getMessage(),500);

            }

        }
    }
?>