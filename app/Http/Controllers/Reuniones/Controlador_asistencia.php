<?php

namespace App\Http\Controllers\Reuniones;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $user = User::select('id', 'nombres', 'paterno', 'materno')->where('ci', $request->ci_estudiante)->first();
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
