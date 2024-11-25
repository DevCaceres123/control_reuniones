<?php

namespace App\Http\Controllers\Reuniones;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Mes;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use PhpParser\Node\Stmt\Return_;

use function Laravel\Prompts\select;

class Controlador_asistencia extends Controller
{

    public $mensaje = [];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrador.reporte.reporteAsistencia');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = User::select('id', 'nombres', 'paterno', 'materno')->where('ci', $request->ci_estudiante)->first();
        $reunion = $user->reuniones()
            ->whereDate('reuniones.entrada', '>=', $request->fecha_inicio)
            ->whereDate('reuniones.salida', '<=', $request->fecha_final)
            ->get();


        $entradaSalidas = DB::table('user_reunion')
            ->where('user_id', $user->id)
            ->select('salida', 'reunion_id', 'entrada') // Selecciona los campos que necesitas
            ->get();

        $pdf = Pdf::loadView('administrador/pdf/reporteControlAsistencia');
        // Retorna el PDF para descargar directamente
        return $pdf->download('reporteControlAsistencia.pdf');
    }


    public function reporte_asistencia(Request $request)
    {

        try {

            $validatedData = $request->validate([
                'ci_estudiante' => 'required|exists:users,ci',
                'fecha_inicio' => 'required|date',
                'fecha_final' => 'required|date',
            ]);
            $user = User::select('id', 'nombres', 'paterno', 'materno')->where('ci', $request->ci_estudiante)->role('estudiante')->first();
            if (!$user) {
                // Redirige de regreso con un mensaje de error en la sesión
                return redirect()->back()->withErrors(['ci_estudiante' => 'cedeula de indentidad no encontrada']);
            }
            $reuniones = $user->reuniones()
                ->whereDate('reuniones.entrada', '>=', $request->fecha_inicio)
                ->whereDate('reuniones.salida', '<=', $request->fecha_final)
                ->get();


            // Reuniones no asistidas por el usuario en el rango de fechas
            $reunionesNoAsistidas = Reunion::whereDate('entrada', '>=', $request->fecha_inicio)
                ->whereDate('salida', '<=', $request->fecha_final)
                ->whereDoesntHave('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->get();

            $entradaSalidas = DB::table('user_reunion')
                ->where('user_id', $user->id)
                ->select('salida', 'reunion_id', 'entrada') // Selecciona los campos que necesitas
                ->get();

            $pdf = Pdf::loadView('administrador/pdf/reporteControlAsistencia', compact('user', 'reuniones', 'entradaSalidas', 'reunionesNoAsistidas'));

            return $pdf->stream();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['ci_estudiante' => $e->getMessage()]);
        }
    }

    public  function reporte_asistencia_final()
    {

        $asistencia_estudiante = $this->obtenerAsistenciaAnual();
        $cantidad_reuniones = $this->obtenerCantidadAsistecniaMes();
        $user = auth()->user()->only(['id', 'nombres', 'paterno', 'materno']);
        $user['rol'] = auth()->user()->getRoleNames()->first();

        $pdf = Pdf::loadView('administrador/pdf/reporteControlAsistenciaAnual', compact('asistencia_estudiante', 'cantidad_reuniones', 'user'));

        return $pdf->stream();
    }

    public function obtenerAsistenciaAnual()
    {
        $meses = [
            'enero',
            'febrero',
            'marzo',
            'abril',
            'mayo',
            'junio',
            'julio',
            'agosto',
            'septiembre',
            'octubre',
            'noviembre',
            'diciembre'
        ];

        // return $usuariosConAsistencias = User::with(['reuniones' => function ($query) {
        //     $query->select('reuniones.id', 'titulo', 'reuniones.entrada') // Selecciona las columnas de la tabla 'reuniones'
        //         ->addSelect('user_reunion.salida as salida_estudiante', 'user_reunion.entrada as entrada_estudiante') // Agrega las columnas de la tabla intermedia
        //         ->join('user_reunion as ur1', 'ur1.reunion_id', '=', 'reuniones.id') // Join con alias 'ur1'
        //         ->whereNotNull('user_reunion.salida') // Asegura que 'salida' no sea nulo
        //         ->whereNotNull('user_reunion.entrada'); // Asegura que 'entrada' no sea nulo
        // }])
        //     ->select('users.id', 'nombres', 'paterno', 'materno')
        //     ->role('estudiante')
        //     ->get();
        $usuariosConAsistencias = User::with(['reuniones' => function ($query) {
            $query->select('reuniones.id', 'titulo', 'reuniones.entrada') // Incluye 'user_id' en el SELECT
                ->whereNotNull('user_reunion.salida') // Asegura que 'salida' no sea nulo
                ->whereNotNull('user_reunion.entrada'); // Asegura que 'entrada' no sea nulo
        }])
            ->select('users.id', 'nombres', 'paterno', 'materno') // Selección de columnas en la tabla 'users'
            ->role('estudiante') // Filtro por rol 'estudiante'
            ->get();



        $total_asistencia = 0;

        $resultado = $usuariosConAsistencias->map(function ($usuario) use ($meses, $total_asistencia) {
            // Inicializar arreglo de asistencias por mes con valores en 0

            $asistenciasPorMes = array_fill_keys($meses, 0);

            // Contar asistencias por mes
            foreach ($usuario->reuniones as $reunion) {


                $mes = Carbon::parse($reunion->entrada)->format('n') - 1; // Obtén el índice del mes (0 a 11)


                $asistenciasPorMes[array_keys($asistenciasPorMes)[$mes]]++;

                $total_asistencia = count($usuario->reuniones);
            }

            //Retornar un arreglo plano con asistencias por mes como columnas directas
            return array_merge(
                [
                    'nombres' => $usuario->nombres . " " . $usuario->paterno . " " . $usuario->materno,
                    'total' => $total_asistencia,
                ],
                $asistenciasPorMes
            );
        });


        // Convertir el resultado a array para usarlo fuera de la colección
        return $resultadoArray = $resultado->toArray();

        return $resultadoArray;
    }

    public function obtenerCantidadAsistecniaMes()
    {



        // Inicializar los meses con valor 0
        $meses = collect([
            'ene' => 0,
            'feb' => 0,
            'mar' => 0,
            'abr' => 0,
            'may' => 0,
            'jun' => 0,
            'jul' => 0,
            'ago' => 0,
            'sep' => 0,
            'oct' => 0,
            'nov' => 0,
            'dic' => 0,
        ]);

        // Mapeo de números de mes a nombres cortos
        $nombreMesesCortos = [
            1 => 'ene',
            2 => 'feb',
            3 => 'mar',
            4 => 'abr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'ago',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dic',
        ];

        // Obtener reuniones agrupadas por mes
        $reunionesPorMes = Reunion::select(
            DB::raw('MONTH(entrada) as mes'),
            DB::raw('COUNT(id) as total_reuniones')
        )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->mapWithKeys(function ($reunion) use ($nombreMesesCortos) {
                // Convertir el número de mes al nombre corto personalizado
                $nombreMes = $nombreMesesCortos[$reunion->mes];
                return [$nombreMes => $reunion->total_reuniones];
            });

        // Combinar los meses inicializados con los datos obtenidos de la base
        $resultadoFinal = $meses->merge($reunionesPorMes);

        return $resultadoFinal;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $user_id)
    {
        $user_estudiante = User::select('id', 'nombres', 'paterno', 'materno')->where('ci', $user_id)->role('estudiante')->get();
        if ($user_estudiante->isEmpty()) {

            $this->mensaje("error", null);

            return response()->json($this->mensaje, 200);
        }


        $this->mensaje("exito", $user_estudiante);

        return response()->json($this->mensaje, 200);
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
    public function update(Request $request, string $id)
    {
        //
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
