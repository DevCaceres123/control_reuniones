<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rol1       = new Role();
        $rol1->name = 'administrador';
        $rol1->save();

        $rol2       = new Role();
        $rol2->name = 'estudiante';
        $rol2->save();

        $rol3       = new Role();
        $rol3->name = 'docente';
        $rol3->save();

        $usuario = new User();
        $usuario->usuario = 'admin';
        $usuario->password = Hash::make('rodry');
        $usuario->ci = '10028685';
        $usuario->nombres = 'Mary';
        $usuario->paterno = 'Chipana';
        $usuario->materno = 'Tarqui';
        $usuario->estado = 'activo';
        $usuario->email = 'rodrigo@gmail.com';
        $usuario->save();

        $usuario->syncRoles(['administrador']);


        Permission::create(['name' => 'inicio.index'])->assignRole($rol1);

        // USUARIO
        Permission::create(['name' => 'admin.index'])->syncRoles([$rol1]);


        Permission::create(['name' => 'admin.usuario.inicio'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.usuario.crear'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.desactivar'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.reset'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.editarRol'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.editarTargeta'])->assignRole($rol1);


        //ROL
        Permission::create(['name' => 'admin.rol.inicio'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.rol.crear'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.rol.editar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.rol.eliminar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.rol.visualizar'])->syncRoles([$rol1]);

        //PERMISOS
        Permission::create(['name' => 'admin.permiso.inicio'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.permiso.crear'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.permiso.editar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.permiso.eliminar'])->syncRoles([$rol1]);

        // REUNIONES
        Permission::create(['name' => 'reunion.index'])->syncRoles([$rol1, $rol2,$rol3]);
        Permission::create(['name' => 'reunion.planificacion.inicio'])->syncRoles([$rol1, $rol2,$rol3]);
        Permission::create(['name' => 'reunion.planificacion.crear'])->syncRoles([$rol1,$rol3]);
        Permission::create(['name' => 'reunion.planificacion.eliminar'])->syncRoles([$rol1,$rol3]);
        Permission::create(['name' => 'reunion.planificacion.verAsistencia'])->syncRoles([$rol1, $rol2,$rol3]);
        Permission::create(['name' => 'reunion.planificacion.crearAsistencia'])->syncRoles([$rol1,$rol3]);
        Permission::create(['name' => 'reunion.planificacion.generarReporte'])->syncRoles([$rol1,$rol3]);

        // PAGOS
        Permission::create(['name' => 'pago.index'])->syncRoles([$rol1]);
        Permission::create(['name' => 'pago.cuotas.inicio'])->syncRoles([$rol1]);

        // REPORTES
        Permission::create(['name' => 'reporte.index'])->syncRoles([$rol1,$rol3]);
        Permission::create(['name' => 'reporte.asistencia.inicio'])->syncRoles([$rol1,$rol3]);
        Permission::create(['name' => 'reporte.cuotas.inicio'])->syncRoles([$rol1]);

        // LECTORES
        Permission::create(['name' => 'lector.inicio'])->syncRoles([$rol1],$rol3);
        Permission::create(['name' => 'lector.nuevo'])->syncRoles([$rol1]);
        Permission::create(['name' => 'lector.editar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'lector.eliminar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'lector.usar'])->syncRoles([$rol1,$rol3]);
        Permission::create(['name' => 'lector.opciones'])->syncRoles([$rol1,$rol3]);
        
    }
}
