<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Controllers\Controller;
use App\Models\Mes;
use App\Models\Pago;
use App\Models\PagoDonacion;
use App\Models\reporte_pago;
use App\Models\Reporte_pago as ModelsReporte_pago;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class Controlador_pagarCuotas extends Controller
{
    public $mensaje = [];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meses = Mes::select('id', 'mes')->get();
        return view('administrador.pago.pagarCuotas', [
            'meses' => $meses,
        ]);
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

        try {
            if ($request->meses == null) {
                throw new \Exception("seleccione un mes de pago");
            }
            $user = User::select('id', 'ci', 'nombres', 'paterno', 'materno')->where('ci', $request->ci_estudiante)->first();


            if (!$user) {
                throw new \Exception(" estudiante no encontrado");
            }
            $mesesPagados = [];
            foreach ($request->meses as $key => $mes) {

                $respuesta = $this->verificarPago($user->id, $mes);

                if ($respuesta != "correcto") {
                    throw new \Exception("ya se registro ese pago");
                }
                $respuesta = $this->verificarDonacion($user->id, $mes);

                if ($respuesta != "correcto") {
                    throw new \Exception("Ya se pago ese mes como donacion");
                }

                $pago = new Pago();
                $pago->titulo = "pagado";
                $pago->fecha_pago = Carbon::now();
                $pago->monto = "10";
                $pago->mes_id = $mes;
                $pago->estudiante_id = $user->id;
                $pago->id_usuario = auth()->user()->id;

                $mesesPagados[] = Mes::where('id', $mes)->first();
                $pago->save();
            }

            $codigo_unico = $this->generarCodigoUnico($pago->id);

            $data['mesesPagados'] = $mesesPagados;
            $data['user'] = $user;
            $data['cod_unico'] = $codigo_unico;


            $reportePago = new ModelsReporte_pago();
            $reportePago->codigo_unico = $codigo_unico;
            $reportePago->reporte_json = json_encode($data);
            $reportePago->user_id = auth()->user()->id;

            $reportePago->save();
            DB::commit();

            $pdf = Pdf::loadView('administrador/pdf/reporteCuotaPagadaEstudiante', $data)
                ->setPaper([0, 0, 400.8, 400.52]); // Ancho y alto en puntos (p.ej., 420x595 para un tamaño personalizado A5 en puntos)

            return $pdf->stream();
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            return redirect()->back()->withErrors(['errores' => $e->getMessage()]);
        }
    }

    function generarCodigoUnico($userId)
    {
        // Obtener el ID del usuario o cualquier otro ID único de tu tabla
        $timestamp = now()->timestamp; // Marca de tiempo actual
        $randomString = Str::random(8); // Cadena aleatoria para mayor seguridad

        // Combina el ID con un timestamp y la cadena aleatoria
        $codigoBase = $userId . $timestamp . $randomString;

        // Aplica hash SHA-256 para encriptar el código
        $codigoUnico = hash('sha256', $codigoBase);

        // Devuelve los primeros 10 caracteres del hash como código único
        return substr($codigoUnico, 0, 10);
    }
    public function pagarCuotasDonacion(Request $request)
    {
        try {
            if ($request->meses == null) {
                throw new \Exception("seleccione un mes de pago");
            }
            $user = User::select('id', 'ci')->where('ci', $request->ci_estudiante)->first();


            if (!$user) {
                throw new \Exception(" estudiante no encontrado");
            }

            foreach ($request->meses as $key => $mes) {

                $respuesta = $this->verificarPago($user->id, $mes);

                if ($respuesta != "correcto") {
                    throw new \Exception("ya se registro ese pago");
                }
                $respuesta = $this->verificarDonacion($user->id, $mes);

                if ($respuesta != "correcto") {
                    throw new \Exception("ya se registro esa donacion");
                }

                $pago = new PagoDonacion();
                $pago->titulo = "pagado";
                $pago->descripcion = $request->descripcion;
                $pago->fecha_pago = Carbon::now();
                $pago->monto = "10";
                $pago->mes_id = $mes;
                $pago->estudiante_id = $user->id;
                $pago->id_usuario = auth()->user()->id;
                $pago->save();
            }


            DB::commit();

            $this->mensaje("exito", "Pago registrado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error " . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function verificarPago($estudiante_id, $mes_id)
    {
        try {

            $anioActual = Carbon::now()->format('Y');
            $pago = Pago::where('estudiante_id', $estudiante_id)
                ->where('mes_id', $mes_id)
                ->whereYear('fecha_pago', $anioActual)
                ->first();
            if (!$pago) {
                return "correcto";
            }
            return "error";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function verificarDonacion($estudiante_id, $mes_id)
    {
        try {

            $anioActual = Carbon::now()->format('Y');
            $pago = PagoDonacion::where('estudiante_id', $estudiante_id)
                ->where('mes_id', $mes_id)
                ->whereYear('fecha_pago', $anioActual)
                ->first();
            if (!$pago) {
                return "correcto";
            }
            return "error";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $user_id)
    {


        $user_estudiante = User::select('id', 'nombres', 'paterno', 'materno')->where('ci', $user_id)->role('estudiante')->first();


        if (!$user_estudiante) {

            $this->mensaje("error", "estudiante no encontrado");

            return response()->json($this->mensaje, 200);
        }
        $anio_actual = Carbon::now()->year;

        $mesesNoPagados = $this->mesesNoPagados($anio_actual, $user_estudiante->id);
        $mesesNoPagadosDonacion = $this->mesesNoPagadosDonacion($anio_actual, $user_estudiante->id);

        // se obtiene la diferencia entre los meses pagoados y meses pagados con donacion, para no repetir el ciclo
        $diferencia = array_uintersect($mesesNoPagados, $mesesNoPagadosDonacion, function ($a, $b) {
            return $a['id'] <=> $b['id'];
        });

        $mesesParseado = array_values($diferencia);
        $datosPago = [
            'meses' => $mesesParseado,
            'estudiante' => $user_estudiante,
        ];

        $this->mensaje("exito", $datosPago);

        return response()->json($this->mensaje, 200);
    }


    public function mesesNoPagados($anio_actual, $estudiante_id)
    {


        $pagos = Pago::where('estudiante_id', $estudiante_id)
            ->whereYear('fecha_pago', $anio_actual) // Suponiendo que 'fecha_pago' es el campo de la fecha en la tabla
            ->get();

        $meses = Mes::all();
        // Obtener solo los IDs de los meses pagados
        $mesesPagadosIds = $pagos->pluck('mes_id')->toArray();


        // Filtrar los meses no pagados usando los IDs
        $mesesNoPagados = $meses->whereNotIn('id', $mesesPagadosIds);


        return $mesesParseado = array_values($mesesNoPagados->toArray());
    }


    public function mesesNoPagadosDonacion($anio_actual, $estudiante_id)
    {


        $pagos = PagoDonacion::where('estudiante_id', $estudiante_id)
            ->whereYear('fecha_pago', $anio_actual) // Suponiendo que 'fecha_pago' es el campo de la fecha en la tabla
            ->get();

        $meses = Mes::all();
        // Obtener solo los IDs de los meses pagados
        $mesesPagadosIds = $pagos->pluck('mes_id')->toArray();


        // Filtrar los meses no pagados usando los IDs
        $mesesNoPagados = $meses->whereNotIn('id', $mesesPagadosIds);


        return $mesesParseado = array_values($mesesNoPagados->toArray());
    }



    public function edit(string $id) {}

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
