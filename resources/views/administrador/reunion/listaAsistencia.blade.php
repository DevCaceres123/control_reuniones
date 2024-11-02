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
                        <div class="card-body">
                            <div class="table-responsive">
                                {{-- tabla para crear usuarioss --}}
                                <table class="table  mb-0 table-centered" id="table_planificacion">
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
                                        @foreach ($asistenciaReunion as $asistencia)
                                            <?php $contador = 1; ?>
                                            @foreach ($asistencia['users'] as $usuarios)
                                                <tr>
                                                    <td>
                                                        {{ $contador++ }}
                                                    </td>

                                                    <td>{{ $usuarios->ci }}</td>
                                                    <td>{{ $usuarios->nombres }}</td>
                                                    <td>{{ $usuarios->paterno }}</td>
                                                    <td>{{ $usuarios->materno }}</td>
                                                    <td>{{$usuarios->entrada}}</td>
                                                    <td>Salida</td>
                                                    <td> <span class="badge bg-danger fs-5">
                                                            ASISTIO
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
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




@endsection

@section('scripts')

@endsection
