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

        DB::beginTransaction();
        try {
            $user = User::select('id', 'ci')->where('ci', $request->ci_estudiante)->first();


            if (!$user) {
                throw new \Exception(" estudiante no encontrado");
            }
            $respuesta = $this->verificarPago($user->id, $request->meses);

            if ($respuesta != "correcto") {
                throw new \Exception(" ya se registro ese pago");
            }
            $pago = new Pago();
            $pago->titulo = "pagado";
            $pago->fecha_pago = Carbon::now();
            $pago->monto = "10";
            $pago->mes_id = $request->meses;
            $pago->estudiante_id = $user->id;
            $pago->id_usuario = auth()->user()->id;
            $pago->save();
            DB::commit();

            $this->mensaje("exito", "Pago registrado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function verificarPago($estudiante_id, $mes_id)
    {
        try {

            $pago = Pago::where('estudiante_id', $estudiante_id)
                ->where('mes_id', $mes_id)
                ->first();
            if (!$pago) {
                return "correcto";
            }

            $verificarAnio = $this->verificarAnioDePAgo($pago->id);

            if ($verificarAnio != "correcto") {
                throw new \Exception("ya se registro ese pago");
            }

            return "correcto";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function verificarAnioDePAgo($pago_id)
    {

        $añoActual = Carbon::now()->format('Y');

        $pago = Pago::find($pago_id);
        $fecha_pago = Carbon::parse($pago->fecha_pago)->format('Y');

        if ($añoActual === $fecha_pago) {
            return "error";
        } else {
            return "correcto";
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

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
