<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE DE CUOTAS PAGADAS</title>
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
            <p><b>NOMBRE DEL ESTUDIANTE:</b> {{ $user->nombres ?? 'N/A' }} {{ $user->paterno ?? 'N/A' }}
                {{ $user->materno ?? 'N/A' }}</p>
            <hr>

        </div>

        <h3 class="titulo">ASISTENCIA</h3>
        <!-- Tabla de asistencia -->
        <table class="tabla">
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>MES</th>
                    <th>MONTO</th>
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
                @foreach ($mesesPagados as $mes)
                    <tr>
                        <td>{{ $count++ }}</td>

                        <td>{{ $mes->mes }}</td>
                        <td>10 <strong>Bs</strong></td>
                        <td>PAGADO</td>
                    </tr>
                @endforeach

                @foreach ($mesesNoPagados as $mes)
                    <tr>
                        <td>{{ $count++ }}</td>
                       
                            <td>{{ $mes->mes }}</td>
                            <td>10 <strong>Bs</strong></td>
                            <td>SIN PAGAR</td>
                       


                    </tr>
                @endforeach
            </tbody>
        </table>



        {{-- <div class="totalBoletas">
            <p class="inasitencia">TOTAL FALTAS: <b>{{ $noAsistencia }}</b></p>
            <p class="observados">TOTAL OBSERVADOS: <b>{{ $observados }}</b></p>
            <p class="asistencia">TOTAL ASISTENTES: <b>{{ $asistencia }}</b></p>
        </div> --}}
    </div>
</body>

</html>
