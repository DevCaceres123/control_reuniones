@extends('principal')
@section('titulo', 'INICIO')
@section('contenido')
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
                    <p class="mb-0 text-truncate text-danger mt-3"><span
                            class="text-success text-bold">{{ $usuariosInactivos }}</span>
                        Usuarios Inactivos</p>
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
                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-success">Mes</span>
                        {{ $nombreMes }}</p>
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
                            <h5 class="mt-2 mb-0 fw-bold">{{ $mesReunion }}</h5>
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
                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-danger"></span>
                    </p>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>



        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0"
                aria-valuemax="100">50%</div>
        </div>


    </div>



@endsection
