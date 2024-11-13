@extends('principal')
@section('titulo', 'usuarioss')
@section('contenido')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">LISTA DE ASISTENCIA</h4>
                        </div>
                        <div class="col-auto">
                            @can('reunion.planificacion.crearAsistencia')
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalAsistencia">
                                <i class="fas fa-plus me-1"></i> Nuevo
                            </button>
                            @endcan

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{-- tabla para crear usuarioss --}}
                                <table class="table  mb-0 table-centered" id="table_asistencia">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nº</th>
                                            <th>CI</th>
                                            <th>NOMBRE</th>
                                            <th>PATERNO</th>
                                            <th>MATERNO</th>
                                            <th>ENTRADA</th>
                                            <th>SALIDA</th>
                                            <th>ASISTENCIA</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $contador = 1; ?>
                                        @foreach ($estudiantesRegistrados as $usuarios)
                                            <tr>
                                                <td>
                                                    {{ $contador++ }}
                                                </td>

                                                <td>{{ $usuarios->ci }}</td>
                                                <td>{{ $usuarios->nombres }}</td>
                                                <td>{{ $usuarios->paterno }}</td>
                                                <td>{{ $usuarios->materno }}</td>

                                                @foreach ($entradaSalidas as $item)
                                                    @if ($usuarios->id == $item->user_id)
                                                        <td>{{ $item->entrada }}</td>
                                                        <td>{{ $item->salida }}</td>

                                                        @if ($item->entrada != '' && $item->salida != '')
                                                            <td><span class="badge bg-success fs-5">PRESENTE</span></td>
                                                        @else
                                                            <td><span class="badge bg-danger fs-5">OBSERVADO</span></td>
                                                        @endif
                                                    @endif
                                                @endforeach

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="ModalAsistencia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="exampleModalLabel"><span
                            class="badge badge-outline-primary rounded">AGREGAR ASISTENCIA</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="nueva_asistencia">
                        <div class="alert alert-success alert-dismissible fade show p-0" role="alert">

                            <div class="text-center p-1">
                                <div class="row">
                                    <i class="fas fa-user-graduate fs-16  text-success"></i>
                                </div>

                                <p class="mt-2 text-success" id="nombre_apellido_res" style="text-transform: uppercase;">
                                    ....</p>
                            </div>
                        </div>

                        <div class="row">

                            <div class="row">
                                <div class="form-group py-2 col-12 col-md-6">
                                    <input type="hidden" name="id_usuarioEstudiante" id="id_usuarioEstudiante">
                                    <input type="hidden" name="id_reunion" id="id_reunion" value={{ $reunion_id }}>
                                    <label for="" class="form-label">CI ESTUDIANTE</label>
                                    <div class="container-validation" id="group_usuarioReset">
                                        <input type="text" class="form-control rounded" name="ci_estudiante"
                                            id="ci_estudiante">
                                        <div class="_ci_estudiante">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group py-2 col-md-6">
                                    <label for="apellidoMaterno_socio" class="form-label">ASISTENCIA</label>

                                    <div class="" id="group_rol_usuario">
                                        <select name="role" id="role" class="form-control  rounded" require>
                                            <option disabled selected>Seleccione una opción</option>

                                            <option class="text-capitalize" value="entrada">Entrada</option>

                                            <option class="text-capitalize" value="salida">Salida</option>
                                        </select>
                                        <div id="_role"></div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                                    class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                            <button type="submit" class="btn btn-success rounded btn-sm" id="btnUser_asistencia"
                                disabled><i class="ri-save-3-line me-1 align-middle"></i> guardar</button>
                        </div>


                    </form>
                </div>
            </div>


        </div>

    </div>



@endsection

@section('scripts')
    <script src="{{ asset('js/modulos/reuniones/listaAsistencia.js') }}" type="module"></script>
@endsection
