@extends('principal')
@section('titulo', 'reunions')
@section('contenido')
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col text-center">
                            <p class="card-title">
                                <span class="badge bg-danger fs-5">
                                    REPORTE DE CUOTAS
                                </span>
                            </p>
                        </div>
                        @if ($errors->has('ci_estudiante'))
                            <div class="alert alert-danger mt-1">
                                {{ $errors->first('ci_estudiante') }}
                            </div>
                        @endif
                        <div class="card-body">
                            <!-- Contenido para generar reportes de ventas -->
                            <form id="form_reporteAsistencia" action="{{ route('cuotas.store') }}" method="POST"
                                target="_blank">
                                @csrf <!-- Token CSRF para proteger el formulario -->
                                <div class="row">
                                    <div class="form-group py-2 col-12 col-md-12">
                                        <label for="ci_estudiante" class="form-label">CI ESTUDIANTE</label>
                                        <input type="text" class="form-control rounded" name="ci_estudiante"
                                            id="ci_estudiante" required>
                                    </div>

                                    <div class="mt-2 row">
                                        <div class="col-8 col-md-4">
                                            <button type="submit"
                                                class="btn btn-success px-2 d-inline-flex align-items-center mb-2"
                                                id="buton_ReporteCuotas" disabled>
                                                <i class="fas fa-calendar-alt fs-20 me-1"></i>Generar reporte
                                            </button>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <a href="{{ route('cuotas.final') }}" target="_blank"
                                                class="btn btn-primary px-2  d-inline-flex align-items-center">
                                                <i class="far fa-file-pdf fs-20 me-1"></i>Generar reporte final

                                            </a>
                                        </div>


                                    </div>


                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 col-lg-5">

            <div class="card p-3">
                <div class="alert alert-success alert-dismissible fade show p-1" role="alert">

                    <div class="text-center pt-1">
                        <div class="row">
                            <i class="fas fa-user-graduate fs-20  text-primary"></i>
                        </div>

                        <p class="mt-1">
                            <strong class=" text-uppercase" id="nombre_apellido_res">

                            </strong>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/modulos/reportes/reporteCuotas.js') }}" type="module"></script>
@endsection
