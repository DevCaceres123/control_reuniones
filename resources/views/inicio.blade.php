@extends('principal')
@section('titulo', 'INICIO')
@section('estilos')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <p class="mb-0 text-truncate text-bold text-light mt-3 bg-dark p-2 rounded">
                           
                            Mes: <span class="text-success"> {{ $nombreMes }}</span>
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
            <div class="col-md-6 col-lg-4">
                <div class="card">

                    <div class="card-body">
                        <h4 class="text-muted">Asistencia</h4>
                        <canvas id="myPieChart" width="400" height="400"></canvas>
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
            var ctx = document.getElementById('myPieChart').getContext('2d');

            var myPieChart = new Chart(ctx, {
                type: 'pie', // Tipo de gráfico: pastel
                data: {
                    labels: ['Faltas', 'Asistencia', 'observados'], // Etiquetas
                    datasets: [{
                        label: 'Distribución de Porcentajes',

                        data: [{{ $datosAsistencia['noAsistio'] }},
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
                        }
                    }
                }
            });
        });
    </script>
@endsection
