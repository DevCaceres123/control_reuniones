@extends('principal')
@section('titulo', 'reunions')
@section('contenido')
    <div class="row">
        <div class="col-6">
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
                            <div class="alert alert-danger">
                                {{ $errors->first('ci_estudiante') }}
                            </div>
                        @endif
                        <div class="card-body">
                            <!-- Contenido para generar reportes de ventas -->
                            <form id="form_reporteAsistencia" action="{{ route('cuotas.store') }}" method="POST">
                                @csrf <!-- Token CSRF para proteger el formulario -->
                                <div class="row">
                                    <div class="form-group py-2 col-12 col-md-12">
                                        <label for="ci_estudiante" class="form-label">CI ESTUDIANTE</label>
                                        <input type="text" class="form-control rounded" name="ci_estudiante"
                                            id="ci_estudiante" required>
                                    </div>
                               
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-outline-success">Generar reporte</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- @section('scripts')
    <script src="{{ asset('js/modulos/reuniones/planificacion.js') }}" type="module"></script>
@endsection --}}
