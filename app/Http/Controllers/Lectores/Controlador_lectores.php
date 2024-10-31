<?php

namespace App\Http\Controllers\Lectores;

use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lector\LectorRequest;
use App\Models\Lector;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class Controlador_lectores extends Controller
{
    public $mensaje = [];

    public function index()
    {
        // $lectores = Lector::with('User')->get();

        $lectores = Lector::with(['user' => function ($query) {
            $query->select('id', 'nombres', 'paterno', 'materno'); // Campos específicos del usuario
        }])->select('id', 'nombre', 'user_id', 'estado', 'uso') // Campos específicos del lector
            ->get();

        // Obtener el ID del usuario autenticado
        // $userId = auth()->user()->id; // O también puedes usar Auth::id();
        // $role = Auth::user()->getRoleNames(); // Devuelve una colección con los nombres de los roles
        return view('administrador.lector.lector', [
            'lectores'  => $lectores,
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
    public function store(LectorRequest $request)
    {


        DB::beginTransaction();

        try {
            // Crear un nuevo usuario
            $lector = new Lector();
            $lector->nombre = $request->nombre;
            $lector->descripcion = $request->descripcion;
            $lector->estado = "inactivo";
            $lector->uso = "inactivo";

            $lector->save();



            // Confirmar la transacción si todo va bien
            DB::commit();

            $this->mensaje("exito", "Lector Registrado Correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $lector_id)
    {
        DB::beginTransaction();

        try {
            $lector = Lector::select('id','nombre','descripcion')->find($lector_id);
            
            // Confirmar la transacción si todo va bien
            DB::commit();

            $this->mensaje("exito", $lector);

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $lector_id)
    {
      
        $uso=$request->uso;
   
        if ($uso !="registro" && $uso !="asistencia") {
            $this->mensaje("error", "Las opciones de lector son incorrectas");
            return response()->json($this->mensaje, 200);
        }

        DB::beginTransaction();

        try {
            $lector = Lector::find($lector_id);
            $lector->estado="activo";
            $lector->uso=$uso;
            $lector->user_id=auth()->user()->id;
            $lector->save();
            // Confirmar la transacción si todo va bien
            DB::commit();

            $this->mensaje("exito", "Lector  listo para utilizar");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $lector_id)
    {
        DB::beginTransaction();

        try {

            $lector = Lector::find($lector_id);


            $lector->delete();



            // Confirmar la transacción si todo va bien
            DB::commit();

            $this->mensaje("exito", "Lector Eliminado Correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    public function terminar_uso($lector_id)
    {

        DB::beginTransaction();

        try {
            $lector = Lector::find($lector_id);
            $lector->estado = "inactivo";
            $lector->uso = "inactivo";
            $lector->user_id = null;
            $lector->save();

            // Confirmar la transacción si todo va bien
            DB::commit();

            $this->mensaje("exito", "Lector desvinculado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function actualizar_lector(Request $request, string $lector_id)
    {
            
        DB::beginTransaction();

        try {
            $lector = Lector::find($lector_id);
            $lector->nombre = $request->nombre;
            $lector->descripcion = $request->descripcion;
            $lector->save();

            // Confirmar la transacción si todo va bien
            DB::commit();

            $this->mensaje("exito", "Lector editado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }
    public function mensaje($titulo, $mensaje)
    {

        $this->mensaje = [
            'tipo' => $titulo,
            'mensaje' => $mensaje
        ];
    }
}
