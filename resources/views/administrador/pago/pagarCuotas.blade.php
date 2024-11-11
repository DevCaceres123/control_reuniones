@extends('principal')
@section('titulo', 'reunions')
@section('contenido')

    <div class="row">
        <div class="col-md-3 m-auto bg-light text-center border-top  border-start border-end border-secondary rounded-top">

            <p class="mt-2">
                <strong class=" text-capitalize" id="nombre_apellido_res">
                    no encontrado...
                </strong>
            </p>

        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-4 m-auto">
            <div class="card">
                <div class="card-header text-center position-relative">
                    <h4 class="card-title mt-1">
                        <span class="badge bg-danger fs-5">PAGAR CUOTAS</span>
                    </h4>

                    <!-- Columna para el monto -->
                    <div class="col-md-3 position-absolute end-0 top-0 ">
                        <p class="p-2 bg-secondary text-white "><strong>10 Bs</strong></p>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Mostrar errores de validación -->
                    @if ($errors->has('ci_estudiante'))
                        <div class="alert alert-danger">
                            {{ $errors->first('ci_estudiante') }}
                        </div>
                    @endif
                    <form id="form_pagarCuotas">
                        @csrf <!-- Token CSRF para proteger el formulario -->
                        <div class="">
                            <label for="ci_estudiante" class="form-label">CI ESTUDIANTE</label>
                            <input type="text" class="form-control rounded" name="ci_estudiante" id="ci_estudiante"
                                required>
                        </div>

                        <div class="row mt-3">
                            <!-- Columna de checkboxes de meses -->
                            <div class="col-md-12">

                                <div class="row border border-secondary   border-2 rounded p-2 ">
                                    <!-- Checkbox para seleccionar todo -->
                                    <div class="col-md-12">
                                        <div class="form-check d-flex justify-content-center align-items-center">
                                            <label class="form-check-label me-4" for="select_all">Seleccionar todo</label>
                                            <input class="form-check-input" type="checkbox" id="select_all"
                                                style="border-color: #007bff">
                                        </div>
                                    </div>
                                    <!-- Checkbox para Listar Meses -->
                                    <div class="row" id="mesesPagados">

                                    </div>

                                </div>
                            </div>

                        </div>

                        <!-- Botón para pagar la cuota -->
                        <div class="mt-3 text-center">
                            <button type="submit"
                                class="btn btn-md btn-outline-primary px-3 d-inline-flex align-items-center"
                                id="buton_PagarCuota">
                                <i class="far fa-money-bill-alt fs-14 me-1"></i>
                                Pagar Cuota
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>


@endsection

@section('scripts')


    <script src="{{ asset('js/modulos/pagos/pagarCuotas.js') }}" type="module"></script>
@endsection
