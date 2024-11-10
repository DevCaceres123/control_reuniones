@extends('principal')
@section('titulo', 'reunions')
@section('contenido')
    <div class="row">
        <div class="col-6 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col text-center">
                            <h4 class="card-title">
                                <span class="badge bg-danger fs-5">
                                    PAGAR CUOTAS
                                </span>
                            </h4>
                        </div>
                        @if ($errors->has('ci_estudiante'))
                            <div class="alert alert-danger">
                                {{ $errors->first('ci_estudiante') }}
                            </div>
                        @endif
                        <div class="card-body">
                            <!-- Contenido para generar reportes de ventas -->
                            <form id="form_pagarCuotas">
                                @csrf <!-- Token CSRF para proteger el formulario -->
                                <div class="row">
                                    <div class="form-group py-2 col-12 col-md-12">
                                        <label for="ci_estudiante" class="form-label">CI ESTUDIANTE</label>
                                        <input type="text" class="form-control rounded" name="ci_estudiante"
                                            id="ci_estudiante" required>
                                    </div>
                                    <div class="form-group py-2 col-8">
                                        <label for="apellidoMaterno_socio" class="form-label">MESES</label>

                                        <div class="" id="group_rol_usuario">
                                            <select name="meses" id="meses" class="form-control  rounded" require>
                                                <option disabled selected>Seleccione una opción</option>
                                                @foreach ($meses as $mes)
                                                    <option class="text-capitalize" value={{ $mes->id }}>
                                                        {{ $mes->mes }}</option>
                                                @endforeach

                                            </select>
                                            <div id="_meses"></div>
                                        </div>
                                    </div>

                                    <div class="form-group py-2 col-md-4">
                                        <label for="" class="form-label">MONTO</label>
                                        <div class=" " id="group_name_usuario">
                                            <input type="" class="form-control rounded" name="usuario" id="usuario"
                                                style="background-color: #f0f0f0;" placeholder="10 Bs" disabled>
                                            <div id="_usuario"></div>
                                        </div>

                                    </div>
                                    <div class="mt-2 d-flex justify-content-center">
                                        <button
                                            class="btn btn-md btn-outline-primary px-2 d-inline-flex align-items-center">
                                            <i class="far fa-money-bill-alt fs-14 me-1"></i>
                                            Pagar Cuota
                                        </button>

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

@section('scripts')
    <script src="{{ asset('js/modulos/pagos/pagarCuotas.js') }}" type="module"></script>
@endsection
