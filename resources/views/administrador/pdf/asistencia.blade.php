<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE DE ASISTENCIA</title>
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
            text-align: center;
            font-size: 12px;
        }

        .tabla th {
            background-color: #080625;
            color: white;
            font-weight: bold;
        }

        .totalBoletas {
            width: 100%;
            position: relative;
        }

        .totalBoletas .inasitencia {
            position: absolute;
            top: 0;
            left: 0;

            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
        }


        .totalBoletas .observados{
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
            <p><b>NOMBRE DE LA REUNIÓN:</b> {{ $reunion->titulo ?? 'N/A' }}</p>
            <hr>
            <p><b>HORA DE INICIO:</b> {{ $reunion->entrada ?? 'N/A' }} | <b>FIN:</b>
                {{ $reunion->salida ?? 'N/A' }}</p>
        </div>

        <h3 class="titulo">ASISTENCIA</h3>
        <!-- Tabla de asistencia -->
        <table class="tabla">
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>CI</th>
                    <th>NOMBRE COMPLETO</th>

                    <th>ENTRADA</th>
                    <th>SALIDA</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                $asistencia = 0;
                $noAsistencia = 0;
                $observados = 0;
                ?>
                @foreach ($asistentes as $index => $registro)
                    <tr>
                        <td>{{ $count ++ }}</td>
                        <td>{{ $registro->ci ?? 'N/A' }}</td>
                        <td>{{ $registro->nombres ?? 'N/A' }} {{ $registro->paterno ?? 'N/A' }}
                            {{ $registro->materno ?? 'N/A' }}</td>
                        @foreach ($entradaSalidas as $item)
                            @if ($registro->id === $item->user_id)
                                <td>{{ $item->entrada ?? 'N/A' }}</td>
                                <td>{{ $item->salida ?? 'N/A' }}</td>
                                @if ($item->entrada != '' && $item->salida != '')
                                    <td>PRESENTE</td>
                                    <?php
                                    $asistencia++;
                                    ?>
                                @else
                                    <td>OBSERVADO</td>
                                    <?php
                                    $observados++;
                                    ?>
                                @endif
                            @endif
                        @endforeach
                    </tr>
                @endforeach

                @foreach ($noAsistentes as $index => $registro)
                    <tr>
                        <td>{{ $count ++ }}</td>
                        <td>{{ $registro->ci ?? 'N/A' }}</td>
                        <td>{{ $registro->nombres ?? 'N/A' }} {{ $registro->paterno ?? 'N/A' }}
                            {{ $registro->materno ?? 'N/A' }}</td>
                        <td>{{ $registro->entrada ?? 'N/A' }}</td>
                        <td>{{ $registro->salida ?? 'N/A' }}</td>
                        <td>FALTA</td>

                        <?php
                        $noAsistencia++;
                        ?>
                    </tr>
                @endforeach
            </tbody>
        </table>



        <div class="totalBoletas">
            <p class="inasitencia">TOTAL FALTAS: <b>{{$noAsistencia}}</b></p>
            <p class="observados">TOTAL OBSERVADOS: <b>{{$observados}}</b></p>
            <p class="asistencia">TOTAL ASISTENTES: <b>{{$asistencia}}</b></p>
        </div>
    </div>
</body>

</html>
