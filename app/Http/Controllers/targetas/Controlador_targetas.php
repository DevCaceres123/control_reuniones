<?php

namespace App\Http\Controllers\targetas;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Lector;
use App\Models\Reunion;
use App\Models\registro_lector;
use Carbon\Carbon;
use Exception;

class Controlador_targetas extends Controller
{
    public function obtenerCodigoTargeta(Request $request)
    {
        if ($request->lector == "" || $request->dato == "") {

            throw new \Exception('error datos no enviados');
        }
        try {
            $lector = $this->reunion($request->lector);

            if ($lector == "error") {
                throw new \Exception('lector no encontrado');
            }


            if ($lector->uso == "registro") {

                return $this->guardarDatosRegistro($lector, $request->dato);
            }

            if ($lector->uso == "asistencia") {

                return $this->registrarAsistencia($request->dato, $lector->id);
            }

            if ($lector->uso == "inactivo") {
                throw new \Exception('Lector no disponible');
            }
        } catch (Exception $e) {
            return "Error " . $e->getMessage();
        }
    }


    public function reunion($lector_id)
    {
        $lector = Lector::find($lector_id);
        if (!$lector) {

            return "error";
        }
        return $lector;
    }

    public function guardarDatosRegistro($lector, $cod_targeta)
    {
        DB::beginTransaction();
      
        try {
            $this->verificar_registro_usuario($lector);
            $registroLector = new registro_lector();
            $registroLector->uso = $lector->uso;
            $registroLector->cod_targeta = $cod_targeta;
            $registroLector->user_id = $lector->user_id;
            $registroLector->save();

            // Confirmar la transacción si todo va bien
            DB::commit();

            return "correcto";
        } catch (Exception $e) {
            DB::rollBack();
            return "error al guardar registro";
        }
    }

    // se verificara se existe un registro en la tabla con el usaurio y si el caso se eliminara 
    // el registro para poder asignarle la nueva targeta
    public function verificar_registro_usuario($lector)
    {


        DB::beginTransaction();
        try {

            $registros = registro_lector::select('id')->where('user_id', $lector->user_id)->get();

            if ($registros->isEmpty()==false) {
                foreach ($registros as $registro) {
                    $registro->delete();
                }
            }

            // Confirmar la transacción si todo va bien
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return "ocurrio un problema al eliminar usuarios repetidos";
        }
    }
    public function registrarAsistencia($codigo_targeta, $lector_id)
    {

        try {
            $reunion = $this->obtenerDatosReunion();

            if ($reunion == "error") {
                throw new \Exception('Ninguna reunion activa');
            }

            $usuario = $this->obtenerDatosUsuario($codigo_targeta);

            if ($usuario == "error") {
                throw new \Exception('Targeta no asignada');
            }

            return $asistencia = $this->validarAsitencia($reunion->tolerancia, $reunion->anticipo, $reunion->salida, $usuario->id, $reunion->id, $lector_id);
        } catch (Exception $e) {
            return "Error " . $e->getMessage();
        }
    }
    public function obtenerDatosReunion()
    {

        $reunion = Reunion::select('id', 'estado', 'tolerancia', 'anticipo', 'salida')->where('estado', 'activo')->first();

        if (!$reunion) {

            return "error";
        }
        return $reunion;
    }

    public function obtenerDatosUsuario($codigo_targeta)
    {
        $usuario = User::select('id')->where('cod_targeta', $codigo_targeta)->first();

        if (!$usuario) {

            return "error";
        }
        return $usuario;
    }


    public function validarAsitencia($tolerancia, $anticipo, $salida, $user_id, $reunion_id, $lector_id)
    {

        $hora_actual = Carbon::now();
        $tolerancia_parseado = Carbon::parse($tolerancia);
        $anticipo_parseado = Carbon::parse($anticipo);
        $salida_parseada = Carbon::parse($salida);


        // Si es correcto iria a la entrada
        if ($hora_actual->between($anticipo_parseado, $tolerancia_parseado)) {

            return $this->registarEntrada($user_id, $reunion_id, $lector_id, $hora_actual);
        }


        // si es correcto ira al atraso
        if ($hora_actual->between($tolerancia_parseado, $salida_parseada)) {

            return $this->registarAtraso($user_id, $reunion_id, $lector_id, $hora_actual);
        }


         // si es correcto ira ala salida
        if ($hora_actual->greaterThan($salida_parseada)) {

            return $this->registarSalida($user_id, $reunion_id, $lector_id, $hora_actual);
        }

        return "No esta en los parametros de fecha";
    }



    public function registarEntrada($user_id, $reunion_id, $lector_id, $hora_actual)
    {
        $usuario = new User();
        $reunion = Reunion::whereHas('users', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->where('id', $reunion_id)->first();


        if (!$reunion) {
            // Intentamos realizar el attach
            try {
                $usuario->reuniones()->attach($reunion_id, [
                    'entrada' => $hora_actual,
                    'user_id' => $user_id,
                    'id_lector' => $lector_id,
                ]);

                return "correcto";
            } catch (\Exception $e) {
                return "Error al registrar asistencia: " . $e->getMessage();
            }
        } else {
            return "Error la entrada ya ah sido registrada";
        }
    }

    public function registarAtraso($user_id, $reunion_id, $lector_id, $hora_actual)
    {
        $usuario = new User();
        $reunion = Reunion::whereHas('users', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->where('id', $reunion_id)->first();


        if (!$reunion) {
            // Intentamos realizar el attach
            try {
                $usuario->reuniones()->attach($reunion_id, [
                    'atraso' => $hora_actual,
                    'user_id' => $user_id,
                    'id_lector' => $lector_id,
                ]);

                return "correcto";
            } catch (\Exception $e) {
                return "Error al registrar asistencia: " . $e->getMessage();
            }
        } else {
            return "Error el atraso ya fue registrado";
        }
    }

    public function registarSalida($user_id, $reunion_id, $lector_id, $hora_actual)
    {
        $usuario = new User();
        $reunion = Reunion::whereHas('users', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->where('id', $reunion_id)->first();


        if (!$reunion) {
            // Intentamos realizar el attach
            try {
                $usuario->reuniones()->attach($reunion_id, [
                    'salida' => $hora_actual,
                    'id_lector' => $lector_id,
                    'user_id' => $user_id,
                ]);

                return "correcto";
            } catch (\Exception $e) {
                return "Error al registrar asistencia: " . $e->getMessage();
            }
        } else {
            $reunion->users()->updateExistingPivot(
                $user_id,
                [
                    'salida' => $hora_actual,
                    'id_lector' => $lector_id,
                ]
            );

            return "correcto";
        }
    }



    public function leerCodigoTargeta()
    {
        // Asegúrate de que el archivo existe
        if (!Storage::disk('local')->exists('data.txt')) {
            return response()->json(['message' => 'El archivo no existe.'], 404);
        }

        // Leer el contenido del archivo
        $content = Storage::disk('local')->get('data.txt');

        return response()->json(['content' => $content]);
    }










    public function agregarCodigoTargeta() {}


    private function datosLector()
    {
        // Verifica si el archivo existe
        if (!Storage::disk('local')->exists('estadoLector.txt')) {
            // Si no existe, lo crea y escribe contenido inicial
            Storage::disk('local')->put('estadoLector.txt', '');
        }

        $estadoLector = Storage::disk('local')->get('estadoLector.txt');

        if ($estadoLector != "lectura") {
            return response()->json(
                [
                    'titulo' => "error",
                    'mensaje'  => "El estado del lector no esta en lectura",
                ],
                200
            );
        }
        // Verifica si el archivo existe
        if (!Storage::disk('local')->exists('data.txt')) {
            // Si no existe, lo crea y escribe contenido inicial
            Storage::disk('local')->put('data.txt', '');
        }


        // Obtiene el contenido del archivo
        $datosTargeta = Storage::disk('local')->get('data.txt');

        return $datosTargeta;
    }
}
