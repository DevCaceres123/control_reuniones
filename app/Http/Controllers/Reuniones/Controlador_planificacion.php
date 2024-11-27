<?php

namespace App\Http\Controllers\Reuniones;

use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Reunion;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reunion\ReunionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class Controlador_planificacion extends Controller
{
    public $mensaje = [];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $reuniones = Reunion::select('id', 'titulo', 'descripcion', 'estado', 'tolerancia', 'anticipo', 'entrada', 'salida')->get();

        return view('administrador.reunion.planificacion', [
            'reuniones'  => $reuniones,
        ]);
    }


    public function listar_reuniones()
    {
        $reuniones = Reunion::select('id', 'titulo', 'descripcion', 'estado', 'tolerancia', 'anticipo', 'entrada', 'salida')
            ->orderBy('id', 'desc')
            ->get()->map(function ($reunion) {
                return [
                    'id' => $reunion->id,
                    'titulo' => $reunion->titulo,
                    'descripcion' => $reunion->descripcion,
                    'estado' => $reunion->estado,
                    'tolerancia' => $reunion->tolerancia,
                    'anticipo' => $reunion->anticipo,
                    'fecha' => Carbon::parse($reunion->entrada)->format('Y-m-d'), // Convertir a Carbon
                    'hora_entrada' => Carbon::parse($reunion->entrada)->format('H:i'), // Convertir a Carbon
                    'hora_salida' => Carbon::parse($reunion->salida)->format('H:i'), // Convertir a Carbon
                ];
            });




        $permissions = [
            'eliminar' => auth()->user()->can('reunion.planificacion.eliminar'),
            'verAsistencia' => auth()->user()->can('reunion.planificacion.verAsistencia'),
            'generarReporte' => auth()->user()->can('reunion.planificacion.crearAsistencia'),

        ];
        return response()->json([
            'reuniones' => $reuniones,
            'permissions' => $permissions,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReunionRequest $request)
    {

        $reuniones = Reunion::select('id')->where('estado', 'activo')->get();

        if ($reuniones->count() > 0) {
            $this->mensaje("error", "Existen reuniones activas");
            return response()->json($this->mensaje);
        }
        // Se valida si es un tipo de dato valido y se crean la tolerancia y anticipo de la hora
        $this->asiganarToleranciaActicipo($request->entrada, $request->salida);

        if ($this->mensaje['tipo'] == "error") {
            return response()->json($this->mensaje, 200);
        }
        // se verifica que las horas ingresadsa sean correctas
        $validarHora = $this->validarHoraEntrada($request->entrada, $request->salida);

        if ($validarHora != null) {
            return $validarHora;
        }
        DB::beginTransaction();
        $entrada = Carbon::parse($request->entrada);
        $salida = Carbon::parse($request->salida);
        $tolerancia = $this->mensaje['mensaje']['tolerancia'];
        $anticipo = $this->mensaje['mensaje']['acticipo'];

        try {
            // Crear un nuevo usuario
            $reunion = new Reunion();
            $reunion->titulo = $request->titulo;
            $reunion->descripcion = $request->descripcion;
            $reunion->estado = "activo";
            $reunion->entrada = $entrada;
            $reunion->salida = $salida;
            $reunion->tolerancia = $tolerancia;
            $reunion->anticipo = $anticipo;
            $reunion->user_id = auth()->user()->id;

            $reunion->save();

            // Confirmar la transacción si todo va bien
            DB::commit();

            $this->mensaje("exito", "Reunion Registrado Correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    // VALIDAR LAS HORA DE ENTRADA
    function validarHoraEntrada($horaEntrada, $horaSalida)
    {
        // echo $horaEntrada."<br>";
        // Obtener la hora actual
        $horaActual = Carbon::now();
        // Convertir las horas de entrada y salida a instancias de Carbon
        $hora_entrada = Carbon::parse($horaEntrada);
        $hora_salida = Carbon::parse($horaSalida);

        // Verificar que la hora de entrada sea mayor o igual a la hora actual y menor o igual a la hora de salida
        if ($hora_entrada->lessThanOrEqualTo($horaActual)) {

            $this->mensaje("error", "La entrada tiene que ser mayor a la hora actual");

            return response()->json($this->mensaje, 200);
        }

        if ($hora_entrada->greaterThanOrEqualTo($hora_salida)) {
            $this->mensaje("error", "La hora de salida tiene que ser mayor a la hora de entrada");

            return response()->json($this->mensaje, 200);
        }
    }

    // VALIDAR TIPO DE DATO Y CREAR TOLERANCIA Y ANTICIPO
    public function asiganarToleranciaActicipo($horaEntrada, $horaSalida)
    {
        try {
            $horaEntrada_parseada = Carbon::parse($horaEntrada);
            $horaSalida_parseada = Carbon::parse($horaSalida);
            if ($horaEntrada_parseada instanceof Carbon) {
            }

            if ($horaSalida_parseada instanceof Carbon) {
            }

            $horaEntrada_sumados = $horaEntrada_parseada->copy()->addMinutes(30);
            $horaEntrada_restados = $horaEntrada_parseada->copy()->subMinutes(15);

            $this->mensaje(
                "exito",
                [
                    'tolerancia' => $horaEntrada_sumados->format('H:i:s'),
                    'acticipo' => $horaEntrada_restados->format('H:i:s'),
                ]
            );
        } catch (\Exception $e) {
            $this->mensaje("error", "No es una fecha valida");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $reunion_id) {}

    public function lista_asistencia($reunion_id)
    {
        $estudiantes = User::role('estudiante')->get();


        $reunion = Reunion::find($reunion_id);

        // Obtener los asistentes a la reunión
        $estudiantesRegistrados = Reunion::find($reunion_id)->users()->role('estudiante')->get();


        // $asistenciaReunion = Reunion::with('users')->where('id', $reunion_id)->get();

        $entradaSalidas = DB::table('user_reunion')
            ->where('reunion_id', $reunion_id)
            ->select('salida', 'user_id', 'entrada', 'atraso') // Selecciona los campos que necesitas
            ->get(); // Cambia first() por get()

        $asistenciaReunion = Reunion::whereHas('users')->get();
        return view('administrador.reunion.listaAsistencia', compact('estudiantesRegistrados', 'entradaSalidas', 'reunion_id'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $reunion_id)
    {

        DB::beginTransaction();

        try {
            $reunion = Reunion::find($reunion_id);
            $reunion->estado = "terminado";
            $reunion->save();
            // Confirmar la transacción si todo va bien
            DB::commit();

            $this->mensaje("exito", "Reunion terminado correctemnte");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    public function reporte_asistencia($reunion_id)
    {

        // Obtener todos los estudiantes
        $estudiantes = User::role('estudiante')->get();
        // $estudiantes=User::all();

        $reunion = Reunion::find($reunion_id);

        // Obtener los asistentes a la reunión
        $asistentes = Reunion::find($reunion_id)->users()->role('estudiante')->get();

        // Filtrar los estudiantes que no asistieron
        $noAsistentes = $estudiantes->diff($asistentes);


        $entradaSalidas = DB::table('user_reunion')
            ->where('reunion_id', $reunion_id)
            ->select('salida', 'user_id', 'entrada', 'atraso') // Selecciona los campos que necesitas
            ->get(); // Cambia first() por get()

        $pdf = Pdf::loadView('administrador/pdf/asistencia', compact('reunion', 'asistentes', 'noAsistentes', 'entradaSalidas'));
        return $pdf->stream();
    }


    public function buscar_usuario(String $user_id)
    {
        $user_estudiante = User::select('id', 'nombres', 'paterno', 'materno')->where('ci', $user_id)->role('estudiante')->get();
        if ($user_estudiante->isEmpty()) {

            $this->mensaje("error", null);

            return response()->json($this->mensaje, 200);
        }


        $this->mensaje("exito", $user_estudiante);

        return response()->json($this->mensaje, 200);
    }


    public function nueva_asistencia(ReunionRequest $request)
    {

        DB::beginTransaction();
        try {
            $respuesta="";
            if ($request->role == "entrada") {

              $respuesta=$this->verificarDatos($request->id_reunion, $request->id_usuarioEstudiante, "entrada");
            }
            if ($request->role == "salida") {

              $respuesta=$this->verificarDatos($request->id_reunion, $request->id_usuarioEstudiante, "salida");
            }

            if($respuesta != "correcto"){
                throw new Exception($respuesta);
            }

            DB::commit();
            $this->mensaje("exito", "Ingresado correctamente");
            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {

            DB::rollBack();
            $this->mensaje("error", "error" . $e->getMessage());
            return response()->json($this->mensaje, 200);
        }
    }

    public function verificarDatos($reunion_id, $user_id, $tipo_entrada)
    {

        $usuario = new User();
        try {
           $reunion = Reunion::whereHas('users', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
                ->where('id', $reunion_id)
                ->with('users') // Carga la relación con los datos del pivote
                ->first();



            if (!$reunion) 
            {

               
              $usuario->reuniones()->attach($reunion_id, [
                    'user_id' => $user_id,
                    $tipo_entrada => Carbon::now(),
                    'manual' => $tipo_entrada . " " . "fue insertado manualmente",
                    "user_manual" => auth()->user()->id,
                ]);
            } else 
            {
                
                $atraso= $reunion->users[0]->pivot->atraso;
                if ($atraso != " " && $tipo_entrada == "entrada") {
                    throw new Exception('no se puede asignar una entrada el usuario ya cuenta con un atraso');
                }

                $reunion->users()->updateExistingPivot(
                    $user_id,
                    [
                        'user_id' => $user_id,
                        $tipo_entrada => Carbon::now(),
                        'manual' =>  $tipo_entrada . " " . "fue insertado manualmente",
                        "user_manual" => auth()->user()->id,
                    ]
                );
            }

            return "correcto";
        } catch (Exception $e) {

            return $e->getMessage();
        }

        //    $user = User::find($user_id)->reuniones()->where('reunion_id', $reunion_id)->get()


        //     return $user->updateExistingPivot();
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }





    public function mensaje($titulo, $mensaje)
    {

        $this->mensaje = [
            'tipo' => $titulo,
            'mensaje' => $mensaje
        ];
    }
}
