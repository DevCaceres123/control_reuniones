<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE DE ASISTENCIA ANUAL</title>
    <style>
        /* Estilos optimizados */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333;
        }

        .container_boleta {
            padding: 15px;
            border: 1px solid #8b5050;
            border-radius: 8px;
            position: relative;
        }

        .info_empresa {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .info_empresa h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .info_empresa p {
            font-size: 14px;
            margin: 4px 0;
        }

        .info_empresa img {
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            border-radius: 5px;
        }

        .detalles_reunion {
            margin-top: 20px;
            font-size: 14px;

            text-transform: capitalize !important;
            position: relative;
        }

        .rol {
            box-sizing: border-box;
            position: absolute;
            right: 0;
            top: 0;
            margin-top: -3px;
            display: inline-block;
            padding: 5px;
            background-color: rgb(9, 92, 88);
            border-radius: 5px;
            color: wheat;

        }

        .detalles_reunion hr {
            margin: 10px 0;
            border: none;
            border-top: 1px solid #ddd;
        }

        .titulo {
            margin-top: 12px;
            text-align: center;
            font-weight: bold;
        }


        .tabla {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tabla th,
        .tabla td {
            border: 1px solid #333;
            padding: 8px;
            text-align: initial;
            font-size: 12px;
            position: relative;

        }

        .tipo {
            display: inline-block;
            margin-left: 15px;
            margin-top: 2px;
            margin-top: -2px;
        }

        .pagado_normal {
            padding: 3px 6px;
            border-radius: 5px;
            background-color: rgba(71, 153, 108, 0.3);
            position: absolute;
            right: 35%;

        }

        .pagado_donacion {
            padding: 3px 6px;
            border-radius: 5px;
            background-color: rgba(99, 11, 18, 0.6);
            position: absolute;
            right: 15%;

        }

        .pagado_titulo {
            padding: 3px;
            border-radius: 5px;
            background-color: #9f9dbd;
        }


        .tabla th {
            background-color: #040314;
            color: white;
            font-weight: bold;
        }

        .contenido_meses {
            display: inline-block;
            width: 35px;
            border-right: 1px solid wheat;
            text-align: center;

        }

        .meses {

            display: inline-block;

            font-weight: bold;
            text-transform: capitalize;

            transform: rotate(-25deg);
        }

        .meses_cantidad {
            display: block;

            width: 35px;
        }

        .estudiante {
            width: 80px;
        }

        .meses_asistencia {
            display: block;
            width: 95%;
            height: auto;
            margin-top: 8px;
            margin-left: 6px;
            font-weight: bold;
            /* o auto */
            /* o left, right */

            letter-spacing: 15px;
            padding: 55px;
            position: relative;
            background-color: rebeccapurple;

        }

        .mesPagados {


            top: 0;
            left: 0;
            text-align: center;
            display: inline-block;
            padding: 5px;
            border-radius: 5px;
            width: 12px;
            height: 14px;
            border: 1px solid #030216;
            margin-right: 3px;


        }

        .donacion {
            background-color: rgba(66, 5, 10, 0.3);
        }

        .normal {

            background-color: rgba(71, 153, 108, 0.3);
        }

        .noPagados {
            background-color: rgba(252, 247, 248, 0.3);
        }

        .totalBoletas {
            width: 100%;
            position: relative;
            height: 50px;
        }

        .totalBoletas .inasitencia {
            position: absolute;
            top: 0;
            left: 0;

            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
        }


        .totalBoletas .observados {
            position: absolute;
            top: 0;
            right: 50%;
            left: 50%;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center
        }

        .totalBoletas .asistencia {
            position: absolute;
            top: 0;
            right: 0;

            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container_boleta">
        <!-- Información de la empresa -->
        <div class="info_empresa">
            <h2>ASOCIACION MISIÓN LUZ DE VIDA</h2>
            <p><b>CELULAR:</b> 69750589</p>
            <p>Zona: Playa Verde del distrito 7 de la Ciudad de El Alto</p>
            <p>FECHA REPORTE: {{ now()->format('d-m-Y') }}</p>
            <img src="assets/logo.jpg" alt="Logo" width="90" height="95">
        </div>

        <!-- Detalles de la reunión -->
        <div class="detalles_reunion">
            <p>
                <b>GENERADO POR:</b>
                {{ $user['nombres'] ?? 'N/A' }} {{ $user['paterno'] ?? 'N/A' }} {{ $user['materno'] ?? 'N/A' }}
                <strong class="rol">{{ $user['rol'] }}</strong>
            </p>

            <hr>

        </div>
        {{-- <div class="totalBoletas">
            <p class="inasitencia">TOTAL PAGOS: <b>{{ $pagos_normales_donacion['total_normal'] }}</b> (Bs)</p>

            <p class="asistencia">TOTAL DONACIONES: <b>{{ $pagos_normales_donacion['total_donacion'] }} </b> (Bs)</p>
        </div> --}}
        <h3 class="titulo">REPORTE FINAL DE ASISTENCIA</h3>
        <!-- Tabla de asistencia -->
        <table class="tabla" border="1">
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>ESTUDIANTE</th>

                    <th>
                        @foreach ($cantidad_reuniones as $key => $item)
                            <p class="contenido_meses">
                                <strong class="meses">{{ $key }}</strong>
                                <strong class="meses_cantidad">{{ $item }}</strong>
                            </p>
                        @endforeach
                    </th>
                    <th>TOTAL</th>



                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                $countTotal = 1;
                $pagoEstudiante_normal = 0;
                $pagoEstudiante_donacion = 0;
                
                $pagoEstudiante_normal_total = 0;
                $observados = 0;
                ?>
                @foreach ($asistencia_estudiante as $index => $reporte)
                    <tr>
                        <td>{{ $countTotal++ }}</td>
                        <td class="estudiante">{{ $reporte['nombres'] }}</td>

                        <td class="meses_asistencia">
                            {{ $reporte['enero'] }}
                            {{ $reporte['febrero'] }}
                            {{ $reporte['marzo'] }}
                            {{ $reporte['abril'] }}
                            {{ $reporte['mayo'] }}
                            {{ $reporte['junio'] }}
                            {{ $reporte['julio'] }}
                            {{ $reporte['agosto'] }}
                            {{ $reporte['septiembre'] }}
                            {{ $reporte['octubre'] }}
                            {{ $reporte['noviembre'] }}
                            {{ $reporte['diciembre'] }}
                        </td>
                        <td style="text-align: center">{{$reporte['total']}}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>




    </div>
</body>

</html>
