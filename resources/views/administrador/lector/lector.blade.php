@extends('principal')
@section('titulo', 'usuarios')
@section('contenido')


    <div class="card p-2">

        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">LISTA DE LECTORES</h4>
            </div>
            <div class="col-auto">
                <div class="col-auto">
                    @can('lector.nuevo')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalLector">
                            <i class="fas fa-plus me-1"></i> Nuevo
                        </button>
                    @endcan
                </div>
            </div>
        </div>

    </div>


    {{-- LECTOR DE TARJETA --}}
    <div class="row">
        @foreach ($lectores as $lector)
            <div class="col-md-6 col-lg-4 rounded">
                <div class="card">
                    <div class="mt-1">
                        <div class="d-flex bg-dark justify-content-between align-items-center  text-light p-2">

                            @if ($lector->estado == 'activo')
                                <p class="mb-0 bg-primary px-2 py-1 rounded">Estado:<strong>{{ $lector->estado }}</strong>
                                </p>
                            @else
                                <p class="mb-0 bg-danger px-2 py-1 rounded">Estado:<strong>{{ $lector->estado }}</strong>
                                </p>
                            @endif

                            <p class="mb-0 px-2 py-1 rounded text-uppercase"><strong>{{ $lector->nombre }}</strong></p>

                            @if ($lector->uso == 'registro' || $lector->uso == 'asistencia')
                                <p class="mb-0 bg-success px-2 py-1 rounded">Uso: <strong>{{ $lector->uso }}</strong>
                                </p>
                            @else
                                <p class="mb-0 bg-danger px-2 py-1 rounded">Uso: <strong>{{ $lector->uso }}</strong></p>
                            @endif


                        </div>
                    </div>
                    <div class="position-relative card-body p-4 color-bg  text-center">
                        @if ($lector->user_id != null)
                            <h4 class="text-light text-uppercase opacity-75 fs-16 mb-0"> {{ $lector['user']->nombres }}
                                {{ $lector['user']->paterno }} {{ $lector['user']->materno }}</h4>
                        @else
                            <h4 class="text-light text-uppercase opacity-75 fs-16 mb-0">Sin Uso...</h4>
                        @endif


                        @if ($lector->user_id != null)
                            <div class="position-absolute top-0 end-0 p-1 m-2 spinner-grow text-light" role="status">
                                <span class="sr-only"></span>
                            </div>
                        @else
                            <div class="position-absolute top-0 end-0 p-1 m-2 spinner-grow text-danger" role="status">
                                <span class="sr-only"></span>
                            </div>
                        @endif

                    </div>

                    <div class="card-body mt-n5 ">

                        <div class="position-relative ">
                            <img src="{{ asset('admin_template/images/logos/lang-logo/lector.png') }}" alt=""
                                class="rounded-circle bg-light thumb-xxl">
                        </div>
                        <div>
                            <p class="text-center"> <b>Escoge el uso del Lector</b></p>
                        </div>

                        <div class="mt-1 ">
                            <form class="formularioLectoresUso">
                                <div class="d-flex justify-content-center align-items-center  text-light">
                                    <input type="hidden" name="lector_id" id="lector_id" value={{ $lector->id }}
                                        class="lector_id">

                                    @if ($lector->estado == 'inactivo')
                                        <div class="form-check form-check-inline">
                                            @can('lector.opciones')
                                                <input class="form-check-input" type="radio" name="opcionLector"
                                                    id="inlineRadio1" value="registro" checked>
                                                <label class="form-check-label" for="inlineRadio1">Registro</label>
                                            @endcan
                                        </div>
                                        <div class="form-check form-check-inline">
                                            @can('lector.opciones')
                                                <input class="form-check-input" type="radio" name="opcionLector"
                                                    id="inlineRadio2" value="asistencia">
                                                <label class="form-check-label" for="inlineRadio2">Asistencia</label>
                                            @endcan
                                        </div>
                                        @can('lector.usar')
                                            <button
                                                class="btn btn-sm btn-outline-primary px-2 d-inline-flex align-items-center uso_lector"
                                                id="btnLector_uso" data-id={{ $lector->id }}>
                                                <i class="fas fa-check-circle fs-14 me-1"></i>
                                                Enviar
                                            </button>
                                        @endcan
                                    @endif

                                    @if ($lector->estado == 'activo')
                                        <a class="btn btn-sm btn-outline-success px-2 d-inline-flex align-items-center terminar_uso"
                                            data-id={{ $lector->id }}>
                                            <i class="iconoir-warning-circle fs-14 me-1"></i>
                                            Terminar
                                        </a>
                                    @endif
                                </div>
                            </form>
                            @if ($lector->estado == 'inactivo')
                                <div class="mt-2 d-flex justify-content-evenly ">
                                    @can('lector.eliminar')
                                        <a class="btn btn-sm btn-outline-danger px-2 d-inline-flex align-items-center eliminar_lector"
                                            data-id={{ $lector->id }}>
                                            <i class="iconoir-trash fs-14  me-1"></i>
                                            Eliminar
                                        </a>
                                    @endcan

                                    @can('lector.editar')
                                        <a class="btn btn-sm btn-outline-warning px-2 d-inline-flex align-items-center editar_lector"
                                            data-id={{ $lector->id }}>
                                            <i class="iconoir-warning-circle fs-14 me-1"></i>
                                            Editar
                                        </a>
                                    @endcan
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <!-- MODAL PARA CREAR LECTOR -->
    <div class="modal fade" id="ModalLector" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="exampleModalLabel"><span
                            class="badge badge-outline-primary rounded">REGISTRAR NUEVO LECTOR</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>


                </div>
                <div class="modal-body">
                    <form id="formularioLectoresCrear">
                        <div class="row">
                            <div class="form-group py-2 col-md-12">
                                <label for="" class="form-label">NOMBRE LECTOR</label>
                                <div>
                                    <input type="text" class="form-control rounded" name="nombre" id="nombre"
                                        placeholder="su nombre" style="text-transform:uppercase;">
                                    <div id="_nombre"></div>
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


                        </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                            class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                    <button type="submit" class="btn btn-success rounded btn-sm" id="btnLector_nuevo"><i
                            class="ri-save-3-line me-1 align-middle"></i> Guardar</button>
                </div>


                </form>
            </div>
        </div>
    </div>


    <!-- MODAL PARA EDITAR LECTOR -->
    <div class="modal fade" id="ModalLector_editar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="exampleModalLabel"><span
                            class="badge badge-outline-primary rounded">EDITAR LECTOR</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>


                </div>
                <div class="modal-body">
                    <form id="formularioLectoresCrearEditar">
                        <div class="row">
                            <div class="form-group py-2 col-md-12">
                                <label for="" class="form-label">NOMBRE LECTOR</label>
                                <div>
                                    <input type="hidden" name="lector_id_edit" id="lector_id_edit">
                                    <input type="text" class="form-control rounded" name="nombre_editar"
                                        id="nombre_editar" placeholder="su nombre" style="text-transform:uppercase;">
                                    <div id="_nombre"></div>
                                </div>
                            </div>

                            <div class="form-group py-2 col-md-12">
                                <label for="" class="form-label">DESCRIPCION</label>
                                <div>

                                    <div class="form-floating">
                                        <textarea name="descripcion_editar" id="descripcion_editar" class="form-control"
                                            placeholder="Ingrese descripcion del lector" id="floatingTextarea"></textarea>
                                        <label for="floatingTextarea">Ingrese descripcion del lector</label>
                                    </div>

                                    <div id="_descripcion"></div>
                                </div>

                            </div>


                        </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                            class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                    <button type="submit" class="btn btn-success rounded btn-sm" id="btnLector_editar"><i
                            class="ri-save-3-line me-1 align-middle"></i> Guardar</button>
                </div>

                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/modulos/lectores/lectores.js') }}" type="module"></script>
@endsection
