@extends('principal')
@section('titulo', 'reunions')
@section('contenido')


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">
                                <span class="badge bg-danger fs-4">
                                    LISTA DE REUNIONES
                                </span>
                            </h4>
                        </div>

                        <div class="col-auto">
                            @can('reunion.planificacion.crear')
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalReunion">
                                    <i class="fas fa-plus me-1"></i> Nuevo
                                </button>
                            @endcan
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                {{-- tabla para crear reunions --}}
                                <table class="table  mb-0 table-centered" id="table_planificacion">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nº</th>
                                            <th>TITULO</th>
                                            <th>DESCRIPCION</th>
                                            <th>ESTADO</th>
                                            <th>REUNION</th>
                                            <th>ANTICIPO</th>
                                            <th>TOLERANCIA</th>
                                            <th class="text-end">ACCION</th>
                                        </tr>
                                    </thead>

                                    {{-- <tbody>

                                        @foreach ($reuniones as $reunion)
                                            <tr>
                                                <td>
                                                    1
                                                </td>

                                                <td>{{$reunion->titulo}}</td>
                                                <td>{{$reunion->descripcion }}</td>
                                                <td>

                                                    @if ($reunion->estado == 'activo')
                                                        <span class="badge bg-success fs-5">
                                                            {{ $reunion->estado }}
                                                        </span>
                                                    @endif

                                                    @if ($reunion->estado == 'inactivo')
                                                        <span class="badge bg-danger fs-5">
                                                            {{ $reunion->estado }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $reunion->fecha }} <b>{{$reunion->hora_entrada }}hasta{{$reunion->hora_salida }}</b></td>
                                                <td>{{ $reunion->tolerancia }}</td>
                                                <td>{{ $reunion->anticipo }}</td>

                                                <td class="text-end">
                                                    <a class="btn btn-sm btn-outline-warning px-2 d-inline-flex align-items-center asignar_targeta"
                                                        data-id="${row.id}">
                                                        <i class="fas fa-id-card fs-16"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-outline-warning px-2 d-inline-flex align-items-center asignar_targeta"
                                                        data-id="${row.id}">
                                                        <i class="fas fa-id-card fs-16"></i>
                                                    </a>
                                                </td>


                                            </tr>
                                        @endforeach

                                    </tbody> --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL PARA CREAR REUNION -->
    <div class="modal fade" id="ModalReunion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="exampleModalLabel"><span
                            class="badge badge-outline-primary rounded">REGISTRAR NUEVA REUNION</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>


                </div>
                <div class="modal-body">
                    <form id="formularioReunion_crear">
                        <div class="row">
                            <div class="form-group py-2 col-md-12">
                                <label for="" class="form-label">TITULO REUNION</label>
                                <div>
                                    <input type="text" class="form-control rounded" name="titulo" id="titulo"
                                        placeholder="Ingrese titulo de la reunion">
                                    <div id="_titulo"></div>
                                </div>
                            </div>

                            <div class="form-group py-2 col-md-12">
                                <label for="" class="form-label">DESCRIPCION</label>
                                <div>

                                    <div class="form-floating">
                                        <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese descripcion del lector"
                                            id="floatingTextarea"></textarea>
                                        <label for="floatingTextarea">Ingrese descripcion del lector</label>
                                    </div>

                                    <div id="_descripcion"></div>
                                </div>

                            </div>

                            <div class="form-group py-2 col-12 col-md-6 ">
                                <label for="" class="form-label">ENTRADA</label>
                                <div>
                                    <input type="time" class="form-control rounded" name="entrada" id="entrada">
                                    <div id="_entrada"></div>
                                </div>

                            </div>

                            <div class="form-group py-2 col-12 col-md-6">
                                <label for="" class="form-label">SALIDA</label>
                                <div>
                                    <input type="time" class="form-control rounded" name="salida" id="salida">
                                    <div id="_salida"></div>
                                </div>

                            </div>


                        </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                            class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                    <button type="submit" class="btn btn-success rounded btn-sm" id="btnReunion_nueva"><i
                            class="ri-save-3-line me-1 align-middle"></i> Guardar</button>
                </div>


                </form>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="{{ asset('js/modulos/reuniones/planificacion.js') }}" type="module"></script>
@endsection
