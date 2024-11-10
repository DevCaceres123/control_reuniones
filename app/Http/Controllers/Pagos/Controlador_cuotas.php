<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Controllers\Controller;
use App\Models\Mes;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class Controlador_cuotas extends Controller
{
    public $mensaje = [];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrador.reporte.reporteCuotas', []);
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
        $anio_actual = Carbon::now()->year;

        $user = User::select('id', 'ci', 'nombres', 'paterno', 'materno')->where('ci', $request->ci_estudiante)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['ci_estudiante' => 'cedeula de indentidad no encontrada']);
        }
        

        $pagos = Pago::where('estudiante_id', $user->id)
            ->whereYear('fecha_pago', $anio_actual) // Suponiendo que 'fecha_pago' es el campo de la fecha en la tabla
            ->get();

        $meses = Mes::all();
        // Obtener solo los IDs de los meses pagados
        $mesesPagadosIds = $pagos->pluck('mes_id')->toArray();

        // Filtrar los meses pagados usando los IDs
        $mesesPagados = $meses->whereIn('id', $mesesPagadosIds);

        // Filtrar los meses no pagados usando los IDs
        $mesesNoPagados = $meses->whereNotIn('id', $mesesPagadosIds);

        $pdf = Pdf::loadView('administrador/pdf/reporteCuotasPagadas', compact('user', 'mesesPagados', 'mesesNoPagados'));
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
