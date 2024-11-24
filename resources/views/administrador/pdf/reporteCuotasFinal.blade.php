<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE DE CUOTAS FINAL</title>
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

        .meses {
            position: relative;
            border-radius: 5px;
            display: inline-block;
            padding: 5px;
            border: 1px solid black;
            width: 12px;
            height: 14px;
            text-align: center;
            font-weight: bold;
            z-index: 100;


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
            <img src="data:image/jpeg;base64,{{ $logoBase64 }}" alt="Logo" width="90" height="95">
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
        <div class="totalBoletas">
            <p class="inasitencia">TOTAL PAGOS: <b>{{ $pagos_normales_donacion['total_normal'] }}</b> (Bs)</p>

            <p class="asistencia">TOTAL DONACIONES: <b>{{ $pagos_normales_donacion['total_donacion'] }} </b> (Bs)</p>
        </div>
        <h3 class="titulo">REPORTE GENERAL DE PAGOS</h3>
        <!-- Tabla de asistencia -->
        <table class="tabla">
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>ESTUDIANTE</th>

                    <th>
                        MESES PAGADOS
                    </th>
                    <th>TOTAL <strong>(Bs)</strong></th>

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
                @foreach ($estudiantesCuotasPagadas as $index => $reporte)
                    <tr>
                        <td>{{ $countTotal++ }}</td>
                        <td>{{ $reporte->nombre_completo }}</td>
                        <td class="td_pagos">
                            @php
                                $meses = [
                                    'enero',
                                    'febrero',
                                    'marzo',
                                    'abril',
                                    'mayo',
                                    'junio',
                                    'julio',
                                    'agosto',
                                    'septiembre',
                                    'octubre',
                                    'noviembre',
                                    'diciembre',
                                ];
                            @endphp

                            @foreach ($meses as $mes)
                                @if (strlen($reporte->$mes) == 0)
                                    <strong class="mesPagados noPagados">{{ $count++ }}</strong>
                                @endif
                                @if (strlen($reporte->$mes) == 8 || strlen($reporte->$mes) == 9)
                                    <strong class="mesPagados normal">{{ $count++ }}</strong>
                                @endif

                                @if (strlen($reporte->$mes) == 10 || strlen($reporte->$mes) == 11)
                                    <strong class="mesPagados donacion">{{ $count++ }}</strong>
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $reporte->total }}</td>

                        {{ $count = 1 }}
                    </tr>
                @endforeach
                @foreach ($estudiantesSinCuotas as $index => $estudiante)
                    <tr>
                        <td>{{ $countTotal++ }}</td>
                        <td>{{ $estudiante['nombres'] }} {{ $estudiante['paterno'] }} {{ $estudiante['materno'] }}
                        </td>
                        <td>
                            Ningun pago echo
                        </td>
                        <td>0</td>
                    </tr>
                @endforeach
            </tbody>
        </table>




    </div>
</body>

</html>

{{-- 
@foreach ($estudiante_pagos_mes as $estudiante)
{{ $pagoEstudiante_normal = 0 }}
<tr>
    <td>{{ $count++ }}</td>

    <td>{{ $estudiante['nombres'] }} {{ $estudiante['paterno'] }} {{ $estudiante['materno'] }}</td>
    <td>
        @foreach ($meses as $mes)
            <p class="meses">{{ $mes->id }}
                @foreach ($estudiante['pagos'] as $pago)
                    @if ($mes->id == $pago['mes_id'])
                        @if ($pago['tipo'] == 'normal')
                            {{ $pagoEstudiante_normal = $pagoEstudiante_normal + 10 }}
                            <strong class="mesPagados normal"></strong>
                        @endif
                        @if ($pago['tipo'] == 'donacion')
                            {{ $pagoEstudiante_donacion = $pagoEstudiante_donacion + 10 }}
                            <strong class="mesPagados donacion"></strong>
                        @endif
                    @endif
                @endforeach
            </p>
        @endforeach

    </td>
    <td style="text-align: center">{{ $pagoEstudiante_normal }} <strong>Bs</strong></td>
    {{ $pagoEstudiante_normal_total = $pagoEstudiante_normal + $pagoEstudiante_normal_total }}

</tr>
@endforeach --}}
