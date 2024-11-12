<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\UsuarioRequest;
use App\Models\Pago;
use App\Models\Reunion;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

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
            'password' => 'required'
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

        // REUNION
        $reunion = Reunion::select('entrada')
            ->where('estado', 'activo')->first();

        $mesReunion = Carbon::parse($reunion->entrada)->locale('es')->translatedFormat('d \d\e F \d\e Y');

        

        $data['mesReunion'] = $mesReunion;

        return view('inicio', $data);
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
