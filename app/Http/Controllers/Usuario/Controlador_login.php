<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\UsuarioRequest;
use App\Models\Mes;
use App\Models\Pago;
use App\Models\Reunion;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class Controlador_login extends Controller
{
    /**
     * @version 1.0
     * @author  Rodrigo Lecoña Quispe <rodrigolecona97@gmail.com>
     * @param Controlador Administrar la parte de usuario resgistrados LOGIN
     * ¡Muchas gracias por preferirnos! Esperamos poder servirte nuevamente
     */


    /**
     * PARA EL INGRESO DEL USUARIO POR USUARIO Y CONTRASEÑA
     */
    private $mensajeError = "Usuario o contraseña inválidos";

    public function ingresar(Request $request)
    {



        if ($this->validarDatos($request)->fails()) {
            return $this->respuestaError('Todos los campos son requeridos');
        }

        if ($this->validarCaptcha($request)->fails()) {
            return $this->respuestaError('error al ingresar el codigo');
        }

        $usuario = $this->buscarUsuario($request->usuario);

        if (!$usuario) {
            return $this->respuestaError($this->mensajeError);
        }

        if ($this->autenticarUsuario($request)) {
            return $this->respuestaExitosa('Inicio de sesión con éxito');
        }

        return $this->respuestaError($this->mensajeError);
    }

    private function validarDatos(Request $request)
    {
        return Validator::make($request->all(), [
            'usuario' => 'required',
            'password' => 'required',

        ]);
    }
    private function validarCaptcha(Request $request)
    {
        return Validator::make($request->all(), [
            'captcha' => 'required|captcha',
        ]);
    }

    private function buscarUsuario($usuario)
    {
        return User::where('usuario', $usuario)->first();
    }

    private function autenticarUsuario(Request $request)
    {
        $credenciales = [
            'usuario' => $request->usuario,
            'password' => $request->password,
            'estado' => 'activo',
        ];

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            return true;
        }

        return false;
    }

    private function respuestaExitosa($mensaje)
    {
        return response()->json(mensaje_mostrar('success', $mensaje));
    }

    private function respuestaError($mensaje)
    {
        return response()->json(mensaje_mostrar('error', $mensaje));
    }
    /**
     * FIN PARA EL INGRESO DEL USUARIO Y CONTRASEÑA
     */

    /**
     * PARA INGRESAR AL INICIO
     */
    public function inicio()
    {


        $data['menu']   = 0;
        // Obtener el usuario autenticado

        // USUARIOS
        $userActivo = User::where('estado', 'activo')->count();
        $userInactivo = User::where('estado', 'inactivo')->count();
        $data['usuariosActivos'] = $userActivo;
        $data['usuariosInactivos'] = $userInactivo;


        // PAGOS O CUOTAS

        $mesActual = Carbon::now(); // Obtiene la fecha actual
        $nombreMes = $mesActual->translatedFormat('F'); // Obtiene el nombre del mes en español
        $pagosDelMes = Pago::whereMonth('fecha_pago', $mesActual->month)->count();

        $data['nombreMes'] = ucfirst($nombreMes); // Capitaliza la primera letra del mes
        $data['pagosDelMes'] = $pagosDelMes;
        $data['cantidadRecaudada'] = $pagosDelMes * 10;

        // REUNION
        $reunion = Reunion::select('entrada')
            ->orderBy('entrada', 'desc')
            ->first();

        if ($reunion) {
            $mesReunion = Carbon::parse($reunion->entrada)->locale('es')->translatedFormat('d \d\e F \d\e Y');
            $hora_entrada = Carbon::parse($reunion->entrada)->format('H:i');
            $data['hora_entrada'] = $hora_entrada;
            $data['mesReunion'] = $mesReunion;
        } else {
            $data['hora_entrada'] = null;
            $data['mesReunion'] = null;
        }



        $data['datosAsistencia'] = $this->asistencia();

        if ($this->asistenciaUltimaReunion() != null) {
            $data['datosAsistenciaUltimo'] = $this->asistenciaUltimaReunion();
        } else {
            $data['datosAsistenciaUltimo'] = $this->asistenciaUltimaReunion();
        }

        $data['pagoAnual'] = $this->pagos_anual();

        

        return view('inicio', $data);
    }



    public function asistencia()
    {

        $total_estudiantes = User::select('id', 'nombres', 'paterno', 'materno')->role('estudiante')->count();
        $total_reuniones = Reunion::select('id')->count();

        // Filtrar los estudiantes que asistieron
        $estudiantes_reunion = Reunion::withCount(['users' => function ($query) {
            $query->where('entrada', '!=', null)
                ->where('salida', '!=', null);
        }])
            ->get();
        $asitencia = 0;
        foreach ($estudiantes_reunion as $value) {
            $asitencia = $value->users_count + $asitencia;
        }

        // Filtrar los estudiantes que no observados
        $estudiantes_reunion = Reunion::withCount(['users' => function ($query) {
            $query->where('entrada', '=', null)
                ->orWhere('salida', '=', null);
        }])
            ->get();
        $observado = 0;
        foreach ($estudiantes_reunion as $value) {
            $observado = $value->users_count + $observado;
        }


        // Filtrar los estudiantes que no asistieron
        $reuniones_con_ausentes = Reunion::withCount('users')
            ->get()
            ->map(function ($reunion) use ($total_estudiantes) {
                $reunion->estudiantes_ausentes_count = $total_estudiantes - $reunion->users_count;
                return $reunion;
            });

        $noAsistio = 0;
        foreach ($reuniones_con_ausentes as $value) {
            $noAsistio = $value->estudiantes_ausentes_count + $noAsistio;
        }
        if ($total_estudiantes == 0 || $total_reuniones == 0) {
            return [
                'asistencia' => 100,
                'observado' => 0,
                'noAsistio' => 0,
            ];
        }
        $total_porcentaje = $total_estudiantes * $total_reuniones;

        return [
            'asistencia' => round($asitencia / $total_porcentaje * 100, 0),
            'observado' => round($observado / $total_porcentaje * 100, 0),
            'noAsistio' => round($noAsistio / $total_porcentaje * 100, 0),
        ];
    }


    public function asistenciaUltimaReunion()
    {

        $total_estudiantes = User::role('estudiante')->count();
        $ultima_reunion = Reunion::select('id', 'entrada')
            ->orderBy('entrada', 'desc')
            ->first();
        if (!$ultima_reunion) {
            return [
                'ultima_asistencia' => null,
                'ultima_observado' => null,
                'ultima_noAsistio' => null,
            ];
        }
        // estudiantes que asistieron
        $estudiantes_asistencia = Reunion::select('id')->withCount(
            ['users' => function ($query) {
                $query->where('entrada', '!=', null)
                    ->where('salida', '!=', null);
            }]
        )
            ->where('id', $ultima_reunion->id)
            ->first();



        // estudiantes observados
        $estudiantes_observados = Reunion::select('id')->withCount(
            ['users' => function ($query) {
                $query->where('entrada', '=', null)
                    ->orWhere('salida', '=', null);
            }]
        )
            ->where('id', $ultima_reunion->id)
            ->first();


        $estudiantes_ausentes = Reunion::select('id')->withCount('users')
            ->where('id', $ultima_reunion->id)
            ->get()
            ->map(function ($reunion) use ($total_estudiantes) {
                $reunion->estudiantes_ausentes_count = $total_estudiantes - $reunion->users_count;
                return $reunion;
            });


        return [
            'ultima_asistencia' => round($estudiantes_asistencia->users_count / $total_estudiantes * 100, 0),
            'ultima_observado' => round($estudiantes_observados->users_count / $total_estudiantes * 100, 0),
            'ultima_noAsistio' => round($estudiantes_ausentes[0]->estudiantes_ausentes_count / $total_estudiantes * 100, 0),
        ];
    }


    function pagos_anual()
    {

        $total_estudiantes = User::role('estudiante')->count();
        $total_meses = Mes::count();
        // Obtener la cantidad de personas que pagaron por mes
        $pagosPorMes = Mes::leftJoin('pagos', function ($join) {
            $join->on('meses.id', '=', 'pagos.mes_id')
                ->whereYear('pagos.fecha_pago', Carbon::now()->year); // Solo filtra los pagos del año actual
        })
            ->select('meses.mes', 'meses.id', DB::raw('COUNT(pagos.id) as cantidad_pagos'))
            ->groupBy('meses.id', 'meses.mes')
            ->orderBy('meses.id')
            ->get();

        $mesesPoecentaguesPagos = [];
        $catidadPagada = 0;

        foreach ($pagosPorMes as $key => $value) {
            // Agregar cada mes con su porcentaje al array, usando el nombre del mes como índice
            $mesesPoecentaguesPagos[$value->mes] = round($value->cantidad_pagos / $total_estudiantes * 100, 0);
            $catidadPagada = ($value->cantidad_pagos * 10) + $catidadPagada;
        }


        return [
            'pagoMeses'=>$mesesPoecentaguesPagos,
            'catidadPagada'=> $catidadPagada,
        ];
    }
    /**
     * FIN PARA INGRESAR AL INICIO
     */

    /**
     * CERRAR LA SESSIÓN
     */
    public function cerrar_session(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $data = mensaje_mostrar('success', 'Finalizó la session con éxito!');
        return response()->json($data);
    }
    /**
     * FIN DE CERRAR LA SESSIÓN
     */
}
