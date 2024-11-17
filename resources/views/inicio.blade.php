@extends('principal')
@section('titulo', 'INICIO')
@section('estilos')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

@endsection
@section('contenido')
    @can('inicio.index')
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                            <div class="col-9">
                                <p class="text-dark mb-0 fw-semibold fs-14">Usuarios Activos</p>
                                <h2 class="mt-2 mb-0 fw-bold">{{ $usuariosActivos }}
                                    <strong class="text-muted fs-6">Usuarios activos

                                    </strong>
                                </h2>



                            </div>
                            <!--end col-->
                            <div class="col-3 align-self-center">
                                <div
                                    class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                    <i class="fas fa-users h1 align-self-center mb-0 text-success"></i>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                        <p class="mb-0 text-truncate text-bold text-light mt-3 bg-dark p-2 rounded"> Usuarios Inactivos :
                            <span class="text-info">{{ $usuariosInactivos }}</span>
                        </p>
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                            <div class="col-9">
                                <p class="text-dark mb-0 fw-semibold fs-14">Cuotas Pagadas</p>
                                <h3 class="mt-2 mb-0 fw-bold">{{ $pagosDelMes }} <strong class="text-muted fs-6">Pagos</strong>
                                </h3>
                            </div>
                            <!--end col-->
                            <div class="col-3 align-self-center">
                                <div
                                    class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                    <i class="icofont-money-bag h1 align-self-center mb-0 text-primary"></i>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                        <div class="d-flex justify-content-around text-light mt-3 bg-dark p-2 rounded">
                            <p class="mb-0 text-truncate text-bold ">
                                Mes: <span class="text-success"> {{ $nombreMes }}</span>

                            </p>

                            <p class="mb-0 text-truncate text-bold  ">
                                Recepcion : <span class="text-success"> {{ $cantidadRecaudada }}(Bs)</span>

                        </div>

                        </p>
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                            <div class="col-9">
                                <p class="text-dark mb-0 fw-semibold fs-14">Ultima
                                    Reunion</p>
                                <h5 class="mt-2 mb-0 fw-bold">{{ $mesReunion ?? 'N/A' }}</h5>
                            </div>
                            <!--end col-->
                            <div class="col-3 align-self-center">
                                <div
                                    class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                    <i class="far fa-calendar-alt h1 align-self-center mb-0 text-warning"></i>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                        <p class="mb-0 text-truncate text-bold mt-3 text-light mt-3 bg-dark p-2 rounded ">Hora reunion: <span
                                class="text-warning">{{ $hora_entrada ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>


            <div class="col-12 col-md-12 col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="text-muted">Pago Anual</h4>
                            <h2 class="bg-dark p-2 text-success rounded">{{$pagoAnual['catidadPagada']}}(Bs)</h2>
                        </div>
                        
                        <!-- Añade un contenedor div para limitar el tamaño -->
                        <div class="chart-container ">
                            <canvas id="porcentajePagoAnual" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-4">
                <div class="card">

                    <div class="card-body">
                        <h4 class="text-muted">Asistencia Anual</h4>
                        <canvas id="porcentajeAnual" width="250" height="250"></canvas>
                    </div>
                    <!--end card-body-->
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-6">
                <div class="card">

                    <div class="card-body">
                        <h4 class="text-muted">Ultima Reunion (Asistencia)</h4>
                        <div>
                            <canvas id="porcentajeMensual" height="320"></canvas>
                        </div>

                    </div>
                    <!--end card-body-->
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let porcentajeAnual = document.getElementById('porcentajeAnual').getContext('2d');
            let porcentajeMensual = document.getElementById('porcentajeMensual').getContext('2d');
            let porcentajePagoAnual = document.getElementById('porcentajePagoAnual').getContext('2d');

            let myPieChart1 = new Chart(porcentajeAnual, {
                type: 'pie', // Tipo de gráfico: pastel
                data: {
                    labels: ['Faltas', 'Asistencia', 'Observados'], // Etiquetas
                    datasets: [{
                        label: 'Distribución de Porcentajes',
                        data: [
                            {{ $datosAsistencia['noAsistio'] }},
                            {{ $datosAsistencia['asistencia'] }},
                            {{ $datosAsistencia['observado'] }}
                        ], // Datos de los porcentajes
                        backgroundColor: ['#ff6384', '#36a2eb',
                            '#fec76f'
                        ], // Colores de los segmentos
                        borderColor: ['#fff', '#fff', '#fff'], // Color del borde
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw +
                                        '%'; // Mostrar porcentaje en el tooltip
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            formatter: (value, ctx) => {
                                let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = (value * 100 / sum).toFixed(2) + "%";
                                return percentage;
                            },
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });


            let myPieChart2 = new Chart(porcentajeMensual, {
                type: 'bar', // Tipo de gráfico: pastel
                data: {
                    labels: ['Faltas', 'Asistencia', 'Observados'], // Etiquetas
                    datasets: [{
                        label: 'Distribución de Porcentajes',
                        data: [
                            {{ $datosAsistenciaUltimo['ultima_noAsistio'] }},
                            {{ $datosAsistenciaUltimo['ultima_asistencia'] }},
                            {{ $datosAsistenciaUltimo['ultima_observado'] }}
                        ], // Datos de los porcentajes
                        backgroundColor: ['#ff6384', '#36a2eb',
                            '#fec76f'
                        ], // Colores de los segmentos
                        borderColor: ['#fff', '#fff', '#fff'], // Color del borde
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Permite que el gráfico se adapte al contenedor
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw +
                                        '%'; // Mostrar porcentaje en el tooltip
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            formatter: (value, ctx) => {
                                // Mostrar el valor directamente como porcentaje
                                return value + '%'; // Solo muestra el valor como porcentaje
                            },
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true, // Asegura que el eje Y empiece desde 0
                            max: 100, // Establece el límite máximo en 100%
                            ticks: {
                                stepSize: 20, // Opcional: el tamaño del paso en el eje Y
                                callback: function(value) {
                                    return value + '%'
                                } // Muestra el valor como porcentaje
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });


            let myPieChart3 = new Chart(document.getElementById('porcentajePagoAnual').getContext('2d'), {
                type: 'bar', // Tipo de gráfico: barras
                data: {
                    labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                        'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                    ],
                    datasets: [{
                        label: 'Distribución de Asistencia Anual',
                        data: [
                            {{ $pagoAnual['pagoMeses']['enero'] }},
                            {{ $pagoAnual['pagoMeses']['febrero'] }},
                            {{ $pagoAnual['pagoMeses']['marzo'] }},
                            {{ $pagoAnual['pagoMeses']['abril'] }},
                            {{ $pagoAnual['pagoMeses']['mayo'] }},
                            {{ $pagoAnual['pagoMeses']['junio'] }},
                            {{ $pagoAnual['pagoMeses']['julio'] }},
                            {{ $pagoAnual['pagoMeses']['agosto'] }},
                            {{ $pagoAnual['pagoMeses']['septiembre'] }},
                            {{ $pagoAnual['pagoMeses']['octubre'] }},
                            {{ $pagoAnual['pagoMeses']['noviembre'] }},
                            {{ $pagoAnual['pagoMeses']['diciembre'] }},
                        ],
                        backgroundColor: ['#ff6310', '#36a2a4', '#fec76f', '#ff9f40', '#4bc0c0',
                            '#ffcd56', '#c9cbcf', '#c44c51', '#6aa84f', '#6fa8dc', '#e06666',
                            '#8e7cc3'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Permite que el gráfico se adapte al contenedor
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw +
                                        '%'; // Muestra el porcentaje
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            formatter: (value, ctx) => {
                                // Mostrar el valor directamente como porcentaje
                                return value + '%'; // Solo muestra el valor como porcentaje
                            },
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true, // Asegura que el eje Y empiece desde 0
                            max: 100, // Establece el límite máximo en 100%
                            ticks: {
                                stepSize: 20, // Opcional: el tamaño del paso en el eje Y
                                callback: function(value) {
                                    return value + '%'
                                } // Muestra el valor como porcentaje
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });


        });
    </script>
@endsection
