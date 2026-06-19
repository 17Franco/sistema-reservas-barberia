<?php
    namespace Barberia\Backend\aplicacion\impl;

use Barberia\Backend\aplicacion\ServicioPDF;
use Barberia\Backend\dominio\Reserva;
use Dompdf\Dompdf;

    class ServicioPDFImpl implements ServicioPDF {

            public function crearPDF(Reserva $reserva, int $idReserva): string {
            // Crear HTML del comprobante
            $logoPath = __DIR__ . "/../public/Logos/logo.png";
            $html = "
                <style>
                    @page {
                        margin: 0px;
                    }
                    body {
                        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                        color: #2c3e50;
                        background-color: #ffffff;
                        margin: 0;
                        padding: 15px;
                        font-size: 14px;
                        line-height: 1.5;
                    }

                    .ticket {
                        max-width: 320px;
                        margin: 0 auto;
                        padding: 25px;
                        border: 1px solid #e9ecef;
                        border-radius: 8px;
                        background-color: #ffffff;
                        /* Efecto sutil de sombra si se visualiza en navegador (Dompdf no siempre la renderiza, pero el borde ayuda) */
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                    }

                    .header {
                        text-align: center;
                        margin-bottom: 25px;
                    }

                    .logo img {
                        max-width: 90px;
                        height: auto;
                        margin-bottom: 12px;
                    }

                    h2 {
                        font-size: 18px;
                        font-weight: 700;
                        color: #1a1a1a;
                        margin: 0;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    }

                    .divider {
                        border-top: 1px dashed #ced4da;
                        margin: 20px 0;
                    }

                    .info-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 10px;
                    }

                    .info-table td {
                        padding: 8px 0;
                        vertical-align: middle;
                    }

                    .label {
                        color: #6c757d;
                        font-weight: 500;
                        text-align: left;
                    }

                    .value {
                        color: #1a1a1a;
                        font-weight: 600;
                        text-align: right;
                    }

                    .footer {
                        text-align: center;
                        margin-top: 25px;
                        font-size: 12px;
                        color: #6c757d;
                    }
                    
                    .barber-icon {
                        color: #1a1a1a;
                        font-size: 14px;
                    }
                </style>

                <div class='ticket'>
                    <div class='header'>
                        <div class='logo'>
                            <img src='{$logoPath}'>
                        </div>
                        <h2>Reserva</h2>
                    </div>

                    <div class='divider'></div>

                    <table class='info-table'>
                        <tr>
                            <td class='label'>Número de Turno</td>
                            <td class='value'>#{$idReserva}</td>
                        </tr>
                        <tr>
                            <td class='label'>Fecha</td>
                            <td class='value'>{$reserva->getFecha()}</td>
                        </tr>
                        <tr>
                            <td class='label'>Horario</td>
                            <td class='value'>{$reserva->getHoraIni()} a {$reserva->getHoraFin()}</td>
                        </tr>
                    </table>

                    <div class='divider'></div>

                    <div class='footer'>
                        <p>Gracias por confiar en nosotros <span class='barber-icon'>💈</span></p>
                    </div>
                </div>
            ";

            // Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->render();

            // Devuelve los bytes del PDF
            return $dompdf->output();
        }
        
    }
?>