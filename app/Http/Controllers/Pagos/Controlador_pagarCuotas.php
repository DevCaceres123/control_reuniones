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
            $user = User::select('id', 'ci')->where('ci', $request->ci_estudiante)->first();


            if (!$user) {
                throw new \Exception(" estudiante no encontrado");
            }

            foreach ($request->meses as $key => $mes) {

                $respuesta = $this->verificarPago($user->id, $mes);

                if ($respuesta != "correcto") {
                    throw new \Exception("ya se registro ese pago");
                }

                $pago = new Pago();
                $pago->titulo = "pagado";
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


        $pagos = Pago::where('estudiante_id', $user_estudiante->id)
            ->whereYear('fecha_pago', $anio_actual) // Suponiendo que 'fecha_pago' es el campo de la fecha en la tabla
            ->get();

        $meses = Mes::all();
        // Obtener solo los IDs de los meses pagados
        $mesesPagadosIds = $pagos->pluck('mes_id')->toArray();


        // Filtrar los meses no pagados usando los IDs
        $mesesNoPagados = $meses->whereNotIn('id', $mesesPagadosIds);


        $mesesParseado = array_values($mesesNoPagados->toArray());

        $datosPago=[
            'meses'=>$mesesParseado,
            'estudiante'=>$user_estudiante,
        ];

        $this->mensaje("exito", $datosPago);

        return response()->json($this->mensaje, 200);
    }


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
