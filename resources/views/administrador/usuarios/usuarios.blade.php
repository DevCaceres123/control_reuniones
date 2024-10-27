@extends('principal')
@section('titulo', 'usuarios')
@section('contenido')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">LISTA DE USUARIOS</h4>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalUsuario">
                                <i class="fas fa-plus me-1"></i> Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{-- tabla para crear usuarios --}}
                                <table class="table  mb-0 table-centered" id="table_user">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nº</th>

                                            <th>NOMBRE</th>
                                            <th>PATERNO</th>
                                            <th>MATERNO</th>

                                            <th>ROL</th>
                                            <th>COD.TARGETA</th>
                                            <th class="text-end">ACCION</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        @foreach ($usuarios as $usuario)
                                            <tr>
                                                <td>
                                                    1
                                                </td>
                                                <td><img src="{{ asset('admin_template/images/logos/lang-logo/slack.png') }}"
                                                        alt="" class="rounded-circle thumb-md me-1 d-inline">
                                                    {{ $usuario->nombres }}
                                                </td>
                                                <td>{{ $usuario->paterno }}</td>
                                                <td>{{ $usuario->materno }}</td>


                                                <td>

                                                    @if ($usuario->roles->isNotEmpty())
                                                        @foreach ($usuario->roles as $role)
                                                            <span class="badge bg-success fs-5">
                                                                {{ $role->name }}
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        <span class="badge bg-danger fs-5">
                                                            Sin roles asignados
                                                        </span>
                                                    @endif

                                                </td>
                                                <td>

                                                    @if ($usuario->cod_targeta == null)
                                                        <span class="badge bg-danger fs-5">
                                                            Sin asignar
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success fs-5">
                                                            {{ $usuario->cod_targeta }}
                                                        </span>
                                                    @endif


                                                </td>
                                                <td class="text-end">
                                                    <button
                                                        class="btn btn-sm btn-outline-danger px-2 d-inline-flex align-items-center">
                                                        <i class="iconoir-trash fs-16"></i>

                                                    </button>
                                                    <button
                                                        class="btn btn-sm btn-outline-info px-2 d-inline-flex align-items-center">
                                                        <i class="fas fa-pencil-alt fs-16"></i>

                                                    </button>
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


    <!-- MODAL PARA CREAR USAURIO -->
    <div class="modal fade" id="ModalUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="exampleModalLabel"><span
                            class="badge badge-outline-primary rounded">REGISTRAR NUEVO USUARIO</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formularioUsuario">
                        <div class="row">
                            <div class="form-group py-2 col-md-6">
                                <label for="" class="form-label">DOCUMENTO DE IDENTIDAD</label>
                                <div class="">
                                    <input type="text" class="form-control rounded" placeholder="CI/Pasaporte"
                                        name="ci" id="ci">
                                    <div id="_ci"></div>
                                </div>

                            </div>

                            <div class="form-group py-2 col-md-6">
                                <label for="nombre_socio" class="form-label">NOMBRE</label>
                                <div class="" id="group_nombre_usuario">
                                    <input type="text" class="form-control rounded" name="nombres" id="nombres"
                                        placeholder="su nombre" style="text-transform:uppercase;">
                                    <div id="_nombres"></div>
                                </div>

                            </div>
                            <div class="form-group py-2 col-md-6">
                                <label for="apellidoPaterno_socio" class="form-label"> APELLIDO PATERNO</label>
                                <div class="" id="group_paterno_usuario">
                                    <input type="text" class="form-control rounded" name="paterno" id="paterno"
                                        placeholder="su apellido Paterno" style="text-transform:uppercase;">
                                    <div id="_paterno"></div>
                                </div>

                            </div>
                            <div class="form-group py-2 col-md-6">
                                <label for="apellidoMaterno_socio" class="form-label">APELLIDO MATERNO</label>
                                <div class=" group_materno_usuario" id="group_materno_usuario">
                                    <input type="text" class="form-control rounded" name="materno" id="materno"
                                        placeholder="su apellido Materno" style="text-transform:uppercase;">
                                    <div id="_materno"></div>
                                </div>

                            </div>
                            <div class="form-group py-2 col-md-6">
                                <label for="apellidoMaterno_socio" class="form-label">ROL</label>

                                <div class="" id="group_rol_usuario">
                                    <select name="role" id="role" class="form-control  rounded" require>
                                        <option disabled selected>Seleccione una opción</option>

                                        @foreach ($roles as $value)
                                            <option class="text-capitalize" value={{ $value->id }}>
                                                {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="_role"></div>
                                </div>
                            </div>
                            <div class="form-group py-2 col-md-6">
                                <label for="" class="form-label">CORREO</label>
                                <div class="" id="group_correo_usuario">
                                    <input type="email" class="form-control rounded" name="email" id="email"
                                        placeholder="juan123@gmail.com">
                                    <div id="_email"></div>
                                </div>

                            </div>
                            <div class="form-group py-2 col-md-6">
                                <label for="" class="form-label">USUARIO</label>
                                <div class=" " id="group_name_usuario">
                                    <input type="text" class="form-control rounded" name="usuario" id="usuario"
                                        style="text-transform:uppercase;background-color: #f0f0f0;" disabled
                                        placeholder="usuario">
                                    <div id="_email"></div>
                                </div>

                            </div>
                            <div class="form-group py-2 col-md-6">
                                <label for="" class="form-label">CONTRASEÑA</label>
                                <div class="" id="group_password_usuario">
                                    <input type="text" class="form-control rounded" name="password" id="password"
                                        disabled style="text-transform:uppercase; background-color: #f0f0f0;"
                                        placeholder="contraseña">
                                    <div id="_password"></div>
                                </div>

                            </div>
                        </div>
                </div>
                <div id="error_formulario" class="mt-1 text-center bg-danger m-3 p-2 rounded text-light exitoText"
                    style="display:none">
                    <spam>

                        <b class="">Error Por favor llene correctamente los campos</b>
                    </spam>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                            class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                    <button type="submit" class="btn btn-success rounded btn-sm" id="btn-user"><i
                            class="ri-save-3-line me-1 align-middle"></i> Guardar</button>
                </div>


                </form>
            </div>
        </div>


    </div>


    <!-- MODAL PARA ASIGNAR TARGETA RFID -->
    <div class="modal fade" id="ModalTargeta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-center modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title " id="exampleModalLabel"><span
                            class="badge badge-outline-primary rounded">REGISTRAR TARGETA</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registrtarCodigoTargeta">

                        <div class="row">
                            <div class="form-group py-2 col-md-8">
                                <label for="" class="form-label">Nº TARGETA</label>
                                <div class="">
                                    <input type="hidden" id="id_usuario_targeta" name="id_usuario_targeta">
                                    <input type="text" class="form-control rounded" placeholder="Codigo de targeta"
                                        name="codigo_targeta" id="codigo_targeta"
                                        style="text-transform:uppercase;background-color: #f0f0f0;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <br><br>
                                <button class="btn btn-success rounded" id="obtenerCodigoTargeta">Obtener</button>
                            </div>
                        </div>
                </div>
                <div id="error_formulario" class="mt-1 text-center bg-danger m-3 p-2 rounded text-light exitoText"
                    style="display:none">
                    <spam>

                        <b class="">Error Por favor llene correctamente los campos</b>
                    </spam>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger rounded btn-sm" data-bs-dismiss="modal"> <i
                            class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                    <button type="submit" class="btn btn-success rounded btn-sm" id="btn-user"><i
                            class="ri-save-3-line me-1 align-middle"></i> Guardar</button>
                </div>


                </form>
            </div>
        </div>


    </div>


@endsection


@section('scripts')

    <script src="{{ asset('js/modulos/adm_usuarios/usuarios.js') }}" type="module"></script>
@endsection
