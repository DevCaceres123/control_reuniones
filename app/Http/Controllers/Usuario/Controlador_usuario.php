<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Http\Requests\Perfil\UpdatePasswordRequest;
use App\Http\Requests\Usuario\Rol\UpdateRolRequest;
use App\Http\Requests\Usuario\Usuario\UsuarioRequest;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\registro_lector;
use App\Models\User;
use Database\Seeders\UsuarioSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Redis;
use PhpParser\Node\Stmt\TryCatch;

use function PHPUnit\Framework\isEmpty;

class Controlador_usuario extends Controller
{
    public $mensaje = [];

    public function index()
    {


        $usuarios = User::with('roles')->get();
        $roles = Role::select('id', 'name')->get();
        $departamentos = Departamento::select('id', 'expedido', 'departamento')->get();
        // Obtener el ID del usuario autenticado
        // $userId = auth()->user()->id; // O también puedes usar Auth::id();
        // $role = Auth::user()->getRoleNames(); // Devuelve una colección con los nombres de los roles
        return view('administrador.usuarios.usuarios', [
            'usuarios'  => $usuarios,
            'roles'  => $roles,
            'departamentos' => $departamentos,
        ]);
    }


    public function show($id_usuario)
    {

        try {
            $user = User::select('id')->find($id_usuario);
            $usuario_actual = auth()->user()->id;
            $registro = registro_lector::select('id', 'cod_targeta')->where('user_id', $usuario_actual)->get();

            if ($registro->isEmpty()) {
                throw new \Exception('No existe targeta escaneada');
            }

            $this->mensaje("exito", [
                'user_id' => $id_usuario,
                'cod_targeta' => $registro[0]->cod_targeta,
            ]);

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error


            $this->mensaje("error", $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    public function store(UsuarioRequest $request)
    {

        DB::beginTransaction();


        try {
            $ci=$request->ci.$request->complemento;
            $respuesta = User::select('id')->where('ci', $ci)->first();
            if ($respuesta) {
                throw new \Exception(" ci ya registrado");
            }

            // Crear un nuevo usuario
            $usuario = new User();
            if ($request->complemento != null) {
                $usuario->ci = $request->ci . "-" . $request->complemento;
            } else {
                $usuario->ci = $request->ci;
            }

            $usuario->nombres = $request->nombres;
            $usuario->paterno = $request->paterno;
            $usuario->materno = $request->materno;
            $usuario->email = $request->email;
            $usuario->estado = "activo";
            $usuario->cod_targeta = $request->cod_targeta;
            $usuario->departamento_id = $request->expedido;
            $usuario->usuario = $request->usuario;
            $usuario->password = bcrypt($request->password);
            // $usuario->rol = $request->usuario_edad;

            // Guardar el nuevo usuario en la base de datos
            $usuario->save();


            $usuario->assignRole(intval($request->role));
            // se busca el copdigo de targeta si se obtiene y se elimina
            $targeta_encontrada = User::where('cod_targeta', $request->cod_targeta)->get();
            if ($targeta_encontrada->isEmpty() == false) {
                registro_lector::where('user_id', auth()->user()->id)->delete();
            }
            // Confirmar la transacción si todo va bien


            DB::commit();

            $this->mensaje("exito", "Usuario Registrado Correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function update(UsuarioRequest $request, $id)
    {
        DB::beginTransaction();
        try {



            // Encontrar el usuario por ID
            $user = User::findOrFail($request->id_usaurio);

            if ($request->estado == "activo") {
                $user->estado = "inactivo";
            }
            if ($request->estado == "inactivo") {
                $user->estado = "activo";
            }


            $user->save();
            DB::commit();

            $this->mensaje("exito", "Estado cambiado Correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    public function edit($id_usuario)
    {


        $user = User::select('id', 'ci', 'nombres', 'paterno', 'materno', 'usuario', 'password')->find($id_usuario);


        $this->mensaje("exito", $user);

        return response()->json($this->mensaje, 200);
    }



    public function resetar_usuario($id_usuario)
    {


        DB::beginTransaction();

        try {

            $user = User::find($id_usuario);

            $user->usuario = $user->ci;
            $user->password = Hash::make($user->ci . "_" . strtolower($user->nombres));

            $user->save();
            DB::commit();
            $this->mensaje("exito", "Usuario reseteado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {

            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    public function editar_rol(UsuarioRequest $request)
    {

        $user = User::find($request->user_id);



        // Encuentra el rol por ID
        $nuevoRol = Role::find($request->rol_id);

        if (!$nuevoRol) {
            // Asigna el nuevo rol, eliminando cualquier rol anterior

            $this->mensaje("error", "Rol no encontrado");

            return response()->json($this->mensaje, 200);
        }

        $user->syncRoles([$nuevoRol->name]);


        $this->mensaje("exito", "Usuario reseteado correctamente");

        return response()->json($this->mensaje, 200);
    }



    public function asignar_targeta(Request $request)
    {
        try {
            if ($request->id_usuario_targeta == "" || $request->codigo_targeta == "") {
                throw new \Exception('ninguna targeta fue encontrada');
            }
            $targeta_encontrada = User::where('cod_targeta', $request->codigo_targeta)->get();
            if ($targeta_encontrada->isEmpty() == false) {
                registro_lector::where('user_id', auth()->user()->id)->delete();
                throw new \Exception('La targeta ya fue registrada');
            }
            $usuario = User::find($request->id_usuario_targeta);

            $usuario->cod_targeta = $request->codigo_targeta;

            $usuario->save();
            registro_lector::where('user_id', auth()->user()->id)->delete();
            return response()->json(
                [
                    'tipo' => "exito",
                    'mensaje' => 'adicionado correctamente',

                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'tipo' => "error",
                    'mensaje' => 'Error ' . $e->getMessage(),

                ],
                200
            );
        }
    }

    //verifica en que estado esta el lector
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
    public function listar()
    {
        $usuarios = User::with('roles')->get();

        $permissions = [
            'desactivar' => auth()->user()->can('admin.usuario.desactivar'),
            'reset' => auth()->user()->can('admin.usuario.reset'),

        ];
        return response()->json([
            'usuarios' => $usuarios,
            'permissions' => $permissions,
        ]);
    }
    /**
     * PARA LA PARTE DEL PERFIL
     */
    public function perfil()
    {
        $menu = 0;
        return view('administrador.perfil', [
            'menu' => $menu
        ]);
    }
    /**
     * FIN DE LA PARTE DE PERFIL
     */

    /**
     * PARA GUARDAR NUEVA CONTRASEÑA
     */
    public function password_guardar(UpdatePasswordRequest $request)
    {
        // Inicia la transacción
        DB::beginTransaction();
        try {
            $user = User::find(Auth::user()->id);

            // Verifica la contraseña actual
            if (!Hash::check($request->password_actual, $user->password)) {
                return response()->json(mensaje_mostrar('error', 'La contraseña actual no es correcta.'), 403);
            }

            // Verifica que las nuevas contraseñas coincidan
            if ($request->password_nuevo !== $request->password_confirmar) {
                return response()->json(mensaje_mostrar('error', 'Las contraseñas nuevas no coinciden.'), 422);
            }

            // Actualiza la contraseña
            $user->password = Hash::make($request->password_nuevo);
            $user->save();

            // Confirma la transacción
            DB::commit();

            return response()->json(mensaje_mostrar('success', 'Contraseña actualizada correctamente.'));
        } catch (\Exception $e) {
            // Si ocurre un error, deshacer todos los cambios realizados en la transacción
            DB::rollBack();
            return response()->json(mensaje_mostrar('error', 'Ocurrió un error al actualizar la contraseña.'), 500);
        }
    }


    public function mensaje($titulo, $mensaje)
    {

        $this->mensaje = [
            'tipo' => $titulo,
            'mensaje' => $mensaje
        ];
    }
    /**
     * FIN DE LA PARTE DE NUEVA CONTRASEÑA
     */
}
