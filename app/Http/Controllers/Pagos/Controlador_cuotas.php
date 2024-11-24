<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Controllers\Controller;
use App\Models\Mes;
use App\Models\Pago;
use App\Models\Reporte_pago_final;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpParser\Node\Stmt\Return_;

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

    public function cuotas_reporte_anual()
    {

        $anio_actual = Carbon::now()->year;

        try {
            $user_estudiante = User::select('id', 'nombres', 'paterno', 'materno')->role('estudiante')->get();
            $estudiantesCuotasPagadas = Reporte_pago_final::select(
                'estudiante_id',
                'nombre_completo',
                'total',
                'tipo',
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
            )
                ->where('anio', $anio_actual)
                ->get();

            //se calculan las donaciones y pagos normales
            

            $pagos_normales_donacion=$this->calcular_pagos_normales_donaciones();
            // Obtener los IDs de estudiantes que tienen cuotas pagadas
            $idsEstudiantesConCuotas = $estudiantesCuotasPagadas->pluck('estudiante_id');

            // Filtrar a los estudiantes que no están en la lista de cuotas pagadas
            $estudiantesSinCuotas = $user_estudiante->filter(function ($estudiante) use ($idsEstudiantesConCuotas) {
                return !$idsEstudiantesConCuotas->contains($estudiante->id);
            });

            // convertimos el json en array
            $estudiantesSinCuotas = json_decode($estudiantesSinCuotas, true);

            // reordenamos los indices desde 0
            $estudiantesSinCuotas = array_values($estudiantesSinCuotas);

            // $estudiante_pagos_mes= User::select('nombres','paterno','materno')->get();
            // Imprimir el resultado


            // obtener datos del usaurio que genra el reporte
            $user = auth()->user()->only(['id', 'nombres', 'paterno', 'materno']);
            $user['rol'] = auth()->user()->getRoleNames()->first();

            $pdf = Pdf::loadView('administrador/pdf/reporteCuotasFinal', compact('estudiantesCuotasPagadas', 'estudiantesSinCuotas', 'user','pagos_normales_donacion'));
            return $pdf->stream();
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['ci_estudiante' => $e->getMessage()]);
        }
    }

    public function  calcular_pagos_normales_donaciones()
    {

        $totales = DB::table('reporte_pago_final')
            ->selectRaw("
        SUM(CASE WHEN enero LIKE 'normal%' THEN 1 ELSE 0 END +
            CASE WHEN febrero LIKE 'normal%' THEN 1 ELSE 0 END +
            CASE WHEN marzo LIKE 'normal%' THEN 1 ELSE 0 END +
            CASE WHEN abril LIKE 'normal%' THEN 1 ELSE 0 END +
            CASE WHEN mayo LIKE 'normal%' THEN 1 ELSE 0 END +
            CASE WHEN junio LIKE 'normal%' THEN 1 ELSE 0 END +
            CASE WHEN julio LIKE 'normal%' THEN 1 ELSE 0 END +
            CASE WHEN agosto LIKE 'normal%' THEN 1 ELSE 0 END +
            CASE WHEN septiembre LIKE 'normal%' THEN 1 ELSE 0 END +
            CASE WHEN octubre LIKE 'normal%' THEN 1 ELSE 0 END +
            CASE WHEN noviembre LIKE 'normal%' THEN 1 ELSE 0 END +
            CASE WHEN diciembre LIKE 'normal%' THEN 1 ELSE 0 END
        ) AS total_normal,
        SUM(CASE WHEN enero LIKE 'donacion%' THEN 1 ELSE 0 END +
            CASE WHEN febrero LIKE 'donacion%' THEN 1 ELSE 0 END +
            CASE WHEN marzo LIKE 'donacion%' THEN 1 ELSE 0 END +
            CASE WHEN abril LIKE 'donacion%' THEN 1 ELSE 0 END +
            CASE WHEN mayo LIKE 'donacion%' THEN 1 ELSE 0 END +
            CASE WHEN junio LIKE 'donacion%' THEN 1 ELSE 0 END +
            CASE WHEN julio LIKE 'donacion%' THEN 1 ELSE 0 END +
            CASE WHEN agosto LIKE 'donacion%' THEN 1 ELSE 0 END +
            CASE WHEN septiembre LIKE 'donacion%' THEN 1 ELSE 0 END +
            CASE WHEN octubre LIKE 'donacion%' THEN 1 ELSE 0 END +
            CASE WHEN noviembre LIKE 'donacion%' THEN 1 ELSE 0 END +
            CASE WHEN diciembre LIKE 'donacion%' THEN 1 ELSE 0 END
        ) AS total_donacion
    ")
            ->first();

        // Resultado como un array
        $resultadoArray = [
            'total_normal' => $totales->total_normal*10,
            'total_donacion' => $totales->total_donacion*10,
        ];

        // Imprimir resultados
        return $resultadoArray;
    }

    public function mensaje($titulo, $mensaje)
    {

        $this->mensaje = [
            'tipo' => $titulo,
            'mensaje' => $mensaje
        ];
    }



     // REPORTE PARA GENERAR ASYNCRONO

    //  public function cuotas_reporte_anual()
    //  {
 
    //      $anio_actual = Carbon::now()->year;
    //      $logoPath = public_path('assets/logo.jpg');
    //      $logo = file_get_contents($logoPath);
    //      $logoBase64 = base64_encode($logo);
    //      try {
    //          $user_estudiante = User::select('id', 'nombres', 'paterno', 'materno')->role('estudiante')->get();
    //          $estudiantesCuotasPagadas = Reporte_pago_final::select(
    //              'estudiante_id',
    //              'nombre_completo',
    //              'total',
    //              'tipo',
    //              'enero',
    //              'febrero',
    //              'marzo',
    //              'abril',
    //              'mayo',
    //              'junio',
    //              'julio',
    //              'agosto',
    //              'septiembre',
    //              'octubre',
    //              'noviembre',
    //              'diciembre'
    //          )
    //              ->where('anio', $anio_actual)
    //              ->get();
 
    //          //se calculan las donaciones y pagos normales
 
 
    //          $pagos_normales_donacion = $this->calcular_pagos_normales_donaciones();
    //          // Obtener los IDs de estudiantes que tienen cuotas pagadas
    //          $idsEstudiantesConCuotas = $estudiantesCuotasPagadas->pluck('estudiante_id');
 
    //          // Filtrar a los estudiantes que no están en la lista de cuotas pagadas
    //          $estudiantesSinCuotas = $user_estudiante->filter(function ($estudiante) use ($idsEstudiantesConCuotas) {
    //              return !$idsEstudiantesConCuotas->contains($estudiante->id);
    //          });
 
    //          // convertimos el json en array
    //          $estudiantesSinCuotas = json_decode($estudiantesSinCuotas, true);
 
    //          // reordenamos los indices desde 0
    //          $estudiantesSinCuotas = array_values($estudiantesSinCuotas);
 
    //          // $estudiante_pagos_mes= User::select('nombres','paterno','materno')->get();
    //          // Imprimir el resultado
 
 
    //          // obtener datos del usaurio que genra el reporte
    //          $user = auth()->user()->only(['id', 'nombres', 'paterno', 'materno']);
    //          $user['rol'] = auth()->user()->getRoleNames()->first();
 
 
 
 
    //          $dompdf = new Dompdf();
 
    //          // Contenido del PDF
    //          $html = view(
    //              'administrador/pdf/reporteCuotasFinal',
    //              [
    //                  'estudiantesCuotasPagadas' => $estudiantesCuotasPagadas,
    //                  'estudiantesSinCuotas' => $estudiantesSinCuotas,
    //                  'user' => $user,
    //                  'pagos_normales_donacion' => $pagos_normales_donacion,
    //                  'logoBase64' => $logoBase64,
    //              ]
    //          )->render();
    //          $dompdf->loadHtml($html);
 
    //          // Opcional: Configuración del tamaño de papel y orientación
    //          $dompdf->setPaper('A4', 'portrait');
 
    //          // Generar el PDF
    //          $dompdf->render();
 
    //          // Convertir el PDF a base64 para enviar en la respuesta
    //          // Obtener el PDF en bruto
    //          $pdfOutput = $dompdf->output();
 
    //          // Devolver el archivo PDF como respuesta con el tipo de contenido adecuado
    //          return response($pdfOutput, 200)
    //              ->header('Content-Type', 'application/pdf');
    //      } catch (Exception $e) {
    //          return redirect()->back()->withErrors(['ci_estudiante' => $e->getMessage()]);
    //      }
    //  }
}
